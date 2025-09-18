<?php
require 'nc_wetter_client.php';

$is_day = $forecast_data['current']['is_day'] == '1' ? "day" : "night";
$weather_code = $forecast_data['current']['weather_code'];

$weather_icon_tag = '<i class="wi '.$weather_codes[$weather_code][$is_day]['font'].'"></i>';
$temperature = round($forecast_data['current']['temperature_2m'], 0);


//Change the link
echo "<div id='weather-font-icon'>".$weather_icon_tag."</div><div id='now_temperature'>".$temperature." °C</div>"
?>