<?php
require_once('vendor/autoload.php'); 
require_once('src/SabreRestAPI.php'); 
require_once('src/SabreRestAPIDB.php'); 
require_once('RequestModel.php'); 

$data  = json_decode(file_get_contents('php://input') , true); 

function send_response($data ,  $state = 200) {
    
    if($state != 200 )
        http_response_code($state); 
    
    echo json_encode($data); 
    exit;

}

if( count($data) > 0 ) { 
    
    if($data['hotel']) { 
        
        $SabreAPI = new \SessionSync\SabreRestAPI(); 
        $request = $SabreAPI->fetch_hotel('POST' , $hotel_avail_route , $request_json);

        send_response(($request->getBody()->getContents()),  200);  
    }

}else { 

    echo send_response(['ERROR' => 'invalid request'],  400); 
    exit; 
}
