<?php 
/**
 *  DB table class for Mapping 
 *  Token with the Sync_token
 */
namespace SessionSync; 

class SabreRestAPIDB  { 

    /**
     * Mysqli Holder this variable is init
     * with Mysqli refenc. 
     */
    private $query; 
    /**
     * Connection status if the connection 
     * is a success. 
     */ 
    private $connection_state = FALSE; 
    /**
     * token expires of the generated 
     * Sabre REST session in Seconds ~= 7 days
     */
    const EXPIRE_IN = 604800; 


    public function __construct(){ 
        $this->up(); 
    } 

    /**
     * start DB connectin 
     * @return void
     */
    private function up(){ 
        try { 
            $this->query = new Mysqli('localhost' , 'root' , '' , 'session');
            $this->connection_state = TRUE; 
        }catch(mysqli_sql_exception $e) {
            throw $e; 
        }
        
    }
    /**
     * Close DB conenciton 
     * @return void
     */
    private function down(){ 
        $this->query->close(); 
        $this->connection_state = FALSE; 
    }


    /**
     * Insert Sabre seesion Token into DB
     * @param string $session_token 
     *      session token from sabre response
     * @return void
     */
    public function insert_session($session_token) { 
        if($this->connection_state) { 
            $insert = $this->query->prepare("
            INSERT INTO session (session_token,expires_in)
            VALUES (? , ?)
            "); 

            $insert->bind_param('s' , $session_token); 
            $insert->bind_param('i', self::EXPIRE_IN); 

            $insert->execute();
        }
    }

    /**
     * Getting the token that are under a week 
     */
    public function get_token_under_week() { 
        //  SELECT UNIX_TIMESTAMP(SYSDATE())+604800 as timestamptoday 
        //, (SELECT FROM_UNIXTIME(timestamptoday)) as fromToday

        # SQL 
        # SELECT * FROM session 
        # WHERE created_at < FROM_UNIXTIME((SELECT UNIX_TIMESTAMP(SYSDATE()) + expire_in))

    }

    public function __distruct() { 
        $this->down(); 
    }
}
