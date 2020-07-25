<?php
error_reporting(E_ALL); 
$_SESSION ?? session_start(); 

use SessionSync\SabreRestAPIDB; 

require_once('vendor/autoload.php'); 
require_once('src/SabreRestAPI.php'); 
require_once('src/SabreRestAPIDB.php'); 
require_once('RequestModel.php'); 


if($_GET['response'] ?? false) { 
    $SabreAPI = new \SessionSync\SabreRestAPI(); 
    $request = $SabreAPI->fetch_hotel('POST' , $hotel_avail_route , $request_json); 
} 

/**
 * to use the client side commuinication with 
 * javascript 
 */
require_once('template.php'); 
