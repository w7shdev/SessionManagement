<?php
/**
 * this is a smaple of the requst 
 * rendering the JSON file  
 * and update the control input 
 */
$schema_content = file_get_contents('./src/getHotelAvail.json'); 
$request_json = json_decode($schema_content , true); 

//airpoprt code 
$request_json['GetHotelAvailRQ']['SearchCriteria']['GeoSearch']['GeoRef']['RefPoint']['Value'] = 'MCT'; 
//set the start date and the end date  
$Date  = new \DateTime(); 
$today = $Date->format('Y-m-d'); 
$after_2_day = $Date->add(new DateInterval('P2D'));
$checkout_date = $after_2_day->format('Y-m-d'); 

$request_json['GetHotelAvailRQ']['SearchCriteria']['RateInfoRef']['StayDateRange']['StartDate'] = $today; 
$request_json['GetHotelAvailRQ']['SearchCriteria']['RateInfoRef']['StayDateRange']['EndDate'] = $checkout_date; 

$hotel_avail_route = '/v2.1.0/get/hotelavail';
