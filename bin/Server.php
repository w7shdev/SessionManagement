<?php
require_once(dirname(__DIR__).'../src/SabreRestAPI.php'); 
require_once(dirname(__DIR__).'../src/SabreRestAPIDB.php'); 
require_once(dirname(__DIR__).'../src/SabreHotelAvail.php'); 
require_once(dirname(__DIR__).'../src/SabreSession.php'); 

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use SessionSync\SabreSession;

require_once (dirname(__DIR__) .'../vendor/autoload.php'); 

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new SabreSession()
        )
    ),
    8080
);

$server->run();
