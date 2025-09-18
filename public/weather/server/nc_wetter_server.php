<?php
$user_data = file_get_contents("Weather/weather_users.json");
$data = json_decode($user_data, true);

$path_to_icons = "https://www.netcontact.at/API/Weather/weather-icons/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $domain = htmlspecialchars($_POST['domain']);
    $domain_token = htmlspecialchars($_POST['nctoken']);
    foreach ($data as $item){
        $domain_token = htmlspecialchars($_POST['nctoken']);
        if ($item['domain'] == $domain and $item['nc_token'] == $domain_token){           
            //GET weather info;
            $latitude = $item['latitude']; 
            $longitude = $item['longitude'];

            $weather_codes = file_get_contents("https://www.netcontact.at/API/Weather/weather_info.json");
            $language_info = file_get_contents("https://www.netcontact.at/API/Weather/langs.json");

            $forecast_data = file_get_contents(
                "https://api.open-meteo.com/v1/forecast?latitude=".$latitude."&longitude=".$longitude."&current=temperature_2m,is_day,weather_code,cloud_cover&hourly=temperature_2m,weather_code&daily=weather_code&timezone=Europe%2FBerlin");
            $response = array($path_to_icons, $forecast_data, $weather_codes, $language_info);
                echo json_encode($response);
                break;
        } 
    };
}
?>
