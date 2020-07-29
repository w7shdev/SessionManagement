<?php 
/**
 *  DB table class for Mapping 
 *  Token with the Sync_token
 */
namespace SessionSync; 
use SessionSync\SabreRestAPI; 

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
            $this->query = new \Mysqli('localhost' , 'root' , '' , 'session');
            $this->connection_state = TRUE; 
        }catch(mysqli_sql_exception $e) {
            throw $e; 
        }
        
    }
    /**
     * Close DB connections 
     * @return void
     */
    private function down(){ 
        $this->query->close(); 
        $this->connection_state = FALSE; 
    }

    /**
     * create a session token in DB and 
     * reutrn the session token for use 
     * 
     * @return \string authrized token session
     */
    public function create_session(){
        $api = new SabreRestAPIDB(); 
        $this->insert_session($api->get_token_session()); 
        return $api->get_token_session();  
    }

    /**
     * Insert Sabre seesion Token into DB
     * @param string $session_token 
     *      session token from sabre response
     * @return void
     */
    private function insert_session($session_token) { 
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
     * get the rows of the valid Sabre session 
     * 
     * @return mixed array | table rows 
     */
    public function get_valid_tokens() { 
        
        $result = []; 

        if($this->connection_state) {
            $query = $this->query->query("SELECT * FROM valid_session"); 
            // get the values 
            while($row = $query->fetch_assoc()){ 
                $result[] = $row; 
            }
        }

        $query->close();
        return $result;
    }


    public function __distruct() { 
        $this->down(); 
    }
}
