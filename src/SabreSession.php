<?php
namespace SessionSync;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SessionSync\SabreRestAPI; 

## TODO check the auto loading PSR-4 
require_once(dirname(__DIR__) .'../vendor/autoload.php'); 


class SabreSession implements MessageComponentInterface {
    protected $clients;

    /**
     * Tokens
     */
    protected static $tokens = []; 

    protected static $token_counter = 0; 
    /**
     * Sabre Object handler 
     */
    private $sabreApi; 

    public function __construct() {
        $this->clients = new \SplObjectStorage;

        if(count(self::$tokens) < 1 ){
            self::$token_counter++; 
            $this->sabreApi = new SabreRestAPI(); 
            self::$tokens[] = [
                'token_access' => $this->sabreApi->get_token(), 
                'count' =>  self::$token_counter
            ]; 
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
        // monitor the sessions 
        $this->list_session_tokens();
        // $conn->send("resource {{$conn->resourceID}}");
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        
        $response  = json_decode($msg); 
       
        if($response->getHotel) { 
            /**
             * we will fetch the hotel now 
             * then we will see if there is any token  
             */
            // $this->fetch_hotel(); 
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


    protected function list_session_tokens(){ 
        foreach(self::$tokens as $token_key => $token) {
            echo 'Token no.', $token_key,"\n\r"; 
            echo "Token session : ", $token['token_access'] , "\n\r"; 
            echo "Token count:", $token['count'] , "\n\r"; 
        }
    }
}
