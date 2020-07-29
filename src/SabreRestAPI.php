<?php
namespace SessionSync; 
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use SessionSync\SabreRestAPIDB; 
class SabreRestAPI { 



    /**
     * HTTP REQUEST Holder
     */
    private $client; 

    /**
     * CERT REST API Endpoint 
     */
    private $apiEndPoint = "https://api-crt.cert.havail.sabre.com"; 
    /**
     * assigned token from the webSocket
     */
    private $access_token; 

    /**
     * user Secret to get the sessionless token
     * @see https://developer.sabre.com/guides/travel-agency/quickstart-guides/get-token
     */
    private $userScert = ''; 

    public function __construct(){ 
        $this->client = new Client(['base_uri' => $this->apiEndPoint]); 
    }

    /**
     * get the the valid token for the request 
     * from the DB not from the SABRE API directly 
     * This is help to maintain the Token management 
     * so that we do not need to ask for a token each
     * 
     * @return \string Sabre access token
     */
    public function get_token(){
        $db = new SabreRestAPIDB(); 

        if($this->is_any_valid_session($db)) { 
            $valid_token = $db->get_valid_tokens(); 
            return $valid_token[0]['session_token']; 
        }else {
            $valid_token = $db->create_session(); 
            return $valid_token; 
        } 
        
    }

    /**
     * check if there is any valid session in DB 
     * 
     * @return \boolean true to available session otherwise false
     */
    public function is_any_valid_session(SabreRestAPIDB $db){
        return (count($db->get_valid_tokens()) > 0); 
    }

    /**
     * Setter for access token 
     * this is for websocket to control the opened 
     * sessions
     * @return \string token session
     */
    public function set_access_token($token) { 
        $this->access_token = $token; 
    }
    /**
     * HEADER for the request once the authentication 
     * token is fetched! 
     * @return \Array  HTTP header for Sabre request 
     */
    private function request_header(){
        $token = (!is_null($this->access_token)) 
            ? $this->access_token
            : $this->get_token(); 

        return [
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => "Bearer {$token}",
            'Application-ID' => '',
        ]; 

    }

    /**
     * Request Sabre for authorization token 
     * @return \string json encode Sabre response   
     */
    private function request_token(){ 

        $clinet = new Client(['base_uri' => $this->apiEndPoint]); 
        $headerRequest = [
            'Authorization' => 'Basic '.$this->userScert,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ]; 

        //set the body
        $body = json_encode('grant_type=client_credentials');
        
        $request = $this->clinet->request('POST', 'v2/auth/token', [
            'headers' => $headerRequest, 
            'body'=> 'grant_type=client_credentials'
        ]);

        //return the response as string 
        return $request->getBody()->getContents(); 
    }

    /**
     * get the access token from sabre 
     * 
     * @return \string authrized token session
     */
    public function get_token_session(){ 
        $SesisonObject  = json_decode($this->request_token()); 

        return $SesisonObject->access_token; 
    }
    /**
     * Sabre response to get the hotel avail 
     * @param \string $http_header type of HTTP request
     * @param \string $route PATH of the API requst 
     * @param \Array $request_body request body of Sabre API 
     * @return \mix Sabre response 
    */ 
    public function fetch_hotel($http_header, $route , $request_body) { 

        $request = $this->client->request($http_header , $route,[
            'headers' => $this->request_header(), 
            'body' => json_encode($request_body)
        ]); 

        return $request; 
    }



}

