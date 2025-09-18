
<?php 
require 'nc_wetter_client.php';
$language = 'de'; // Change language
?>
<div id="weather-table">
 	<div class="table">
             
        <?php 
            for ($x = 0; $x <= $days; $x++) {  
                $dayofweek = $language_info['days'][$language][date('w', strtotime("+".$x." day"))];
                
                echo '<div class="description">'.$dayofweek.' - '.date("d. F Y", strtotime("+".$x." day"));
                echo '</div>';
                echo '<div class="row" id="wt-today+"'.$x.'>';
                echo '<div class="time-header">';
                for ($t = 0; $t<count($daytimes); $t++){
                    echo '<p class="day-times-text">'.$language_info['day_time'][$language][$t].'</p>';  
                                                       
                }
                for ($t = 0; $t<count($daytimes); $t++){
                     $values= array_values($daytimes);
                     $hourly_index = $values[$t] + 24 * $x; 
                     echo '
                        <p2 class="temp-text">'.round($forecast_data["hourly"]["temperature_2m"][$hourly_index], 0).'°C</p2>'; 
                                    
                }
                
                echo '</div>';
                echo '</div>';
           	    echo  '<div class="weather_detail">';
                foreach ($daytimes as $daytime){
                    
                    $hourly_index = $daytime + 24 * $x;
                    $hourly_code =  $forecast_data["hourly"]["weather_code"][$hourly_index];                    
                    echo '<div><img src="'
                        .$path.$weather_codes[$hourly_code]['day']['image'].'">
                        </div>'; 
                    };
			    echo '</div>
                    </div>';
            }
?>
    </div>
</div>