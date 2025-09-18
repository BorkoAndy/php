<?php
//Installing all variables
$domain = "https://www.obertauern-webcam.de/";
$nctoken = "SpPIzm9hw5iFZ8lK4EQCkIPvdcp3yGy7TKTB59FVF3UXq4I8fGQVY8tgBwOXj4QM";

$days = 3; //For how many day make the forecast

$daytimes = array(   
    // "night" => 2,
    "morning"=> 7, 
    "noon"=> 12, 
    "afternoon"=> 16,
    "evening"=> 22    
);



//POST - reuest - CURL option
$request = curl_init();

curl_setopt($request, CURLOPT_URL,"https://www.netcontact.at/API/nc_wetter_server.php");
curl_setopt($request, CURLOPT_POST, 1);
curl_setopt($request, CURLOPT_POSTFIELDS,
        "domain=".$domain."&nctoken=".$nctoken);

// catch the response
curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($request);

curl_close ($request);

if ($response == "exit") {
    echo "Something ist wrong. Contact info@nc-werbung.at";
    exit();
}

$data = json_decode($response);

// Getting path
$path = $data["0"];

// Getting weather forecast data
$forecast_data = json_decode($data["1"], true);

// Getting weather codes
$weather_codes = json_decode($data["2"], true);

// Getting language pack
$language_info = json_decode($data["3"], true);

?>
<!-- Load base css from server -> link rel -->

<link rel="stylesheet" href="../css/wetter-style.css">
<link rel="stylesheet" href="../css/wetter-style.css">