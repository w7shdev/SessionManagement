<?php
namespace SessionSync;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
// use GuzzleHttp\Client;
use SessionSync\SabreRestAPI; 
use SessionSync\SabreHotelAvail;

## TODO check the auto loading PSR-4 
require_once(dirname(__DIR__) .'../vendor/autoload.php'); 
require_once(dirname(__DIR__) .'/src/SabreHotelAvail.php'); 


class SabreSession implements MessageComponentInterface {
    protected $clients;

    /**
     * Tokens
     */
    protected static $tokens = []; 

    /**
     * Sabre Object handler 
     */
    private $sabreApi; 

    public function __construct() {
        $this->clients = new \SplObjectStorage;

        if(count(self::$tokens) < 1 ){
            
            $this->sabreApi = new SabreRestAPI(); 
            self::$tokens[] = [
                'token_access' => $this->sabreApi->get_token(), 
                'count' => 0
            ]; 
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
        
        $this->list_session_tokens();
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        
        $response  = json_decode($msg); 
       
        if($response->getHotel) { 
            $this->start_session(); 
            
          $from->send($this->get_hotel_list()); 

            $this->close_session(); 
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * Get the hotel list 
     * @return \Object client response 
     */
    private function get_hotel_list(){ 
        $hotelAvail = new SabreHotelAvail();

        return $hotelAvail->get_response(); 
    }
    protected function list_session_tokens(){ 
        foreach(self::$tokens as $token_key => $token) {
            echo 'Token no.', $token_key,"\n\r"; 
            echo "Token session : ", $token['token_access'] , "\n\r"; 
            echo "Token count:", $token['count'] , "\n\r"; 
        }
    }

    /**
     * Token index 
     */
    private $current_session; // refer to the index of the token 
    /**
     *token string 
     */
    private $token_session;
    /**
     * Get the token session size 
     *  
     *@return \integer session token array size 
     */
    protected function count_token(){ 
        return count(self::$tokens); 
    }
    
    /**
     * Set the session index of the $tokens
     * and the session string and increment the 
     * session token
     * 
     * @return \void 
     */
    protected function start_session(){

        if($this->count_token() == 0 ) { 
            $this->current_session = 0; 
        }else{ 
            $this->current_session = $this->get_avilable_session(); 
        }

        //we can do this 
        $this->token_session = self::$tokens[$this->current_session]['token_access']; 
        self::$tokens[$this->current_session]['count']++; 
    }

    /**
     * Empty session holder remove the counter 
     * so another client can use the same token 
     * 
     * @return \void 
     */
    protected function close_session(){ 
        self::$tokens[$this->current_session]['count']--;
        $this->current_session = null; 
        $this->token_session = null;  
    }

    /**
     * get the session that the counter of it 
     * is under 50 
     */
    protected function get_avilable_session(){ 
        $session_index = 0; 
        foreach(self::$tokens as $index => $token) { 
            if($token['count'] < 50)  { 
                $session_index = $index; 
            break; 
            }
        }

        return $session_index; 
    }

}
