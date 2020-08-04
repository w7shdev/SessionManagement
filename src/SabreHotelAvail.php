<?php 
namespace SessionSync; 
use SessionSync\SabreRestAPI; 
use GuzzleHttp\Client;

class SabreHotelAvail extends SabreRestAPI{ 

    /**
     * path to the request model 
     */
    private $path; 
    /**
     * route of the HTTP request 
     */
    private $uri; 

    public function __construct(){
        parent::__construct(); 
        $this->path = dirname(__DIR__); 
    }

    /**
     * Set the request data model 
     * 
     * @return \array Request body
     */
    public function set_up_request(){
        require $this->path.'/RequestModel.php';         
        $this->uri = $hotel_avail_route; 
        return $request_json; 
    }
    
    /**
     * initiate the request 
     * @return \string Response JSON string 
     */
    private function request(){ 
        $request_body =  $this->set_up_request(); 
        $request = $this->fetch_hotel('POST' , $this->uri , $request_body); 
        return $request->getBody()->getContents(); 
    }

    /**
     * Get the response of the request 
     * @return \string JSON encoded response 
     */
    public function get_response(){ 
        return $this->request(); 
    }



}
