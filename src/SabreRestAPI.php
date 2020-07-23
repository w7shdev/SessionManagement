<?php
namespace SessionSync; 
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
class SabreRestAPI { 

    private $apiEndPoint = "https://api-crt.cert.havail.sabre.com"; 

    /**
     * user Secret to get the sessionless token
     * @see https://developer.sabre.com/guides/travel-agency/quickstart-guides/get-token
     */
    private $userScert = ''; 

    public function __construct(){ 
       var_dump( $this->parse_token() );
    }


    public function parse_token() { 
        return json_decode($this->request_token());
    }
    
    private function request_token(){ 

        $clinet = new Client(['base_uri' => $this->apiEndPoint]); 
        $headerRequest = [
            'Authorization' => 'Basic '.$this->userScert,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ]; 

        //set the body
        $body = json_encode('grant_type=client_credentials');
        
        $request = $clinet->request('POST', 'v2/auth/token', [
            'headers' => $headerRequest, 
            'body'=> 'grant_type=client_credentials'
        ]);

        //return the response as string 
        return $request->getBody()->getContents(); 
    }



}

// new SabreRestAPI(); 