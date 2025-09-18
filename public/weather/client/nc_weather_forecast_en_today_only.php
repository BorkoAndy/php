
<?php /*
require 'nc_wetter_client.php';
$language = 'en'; // Change language */
?>
<div id="weather-table">
 	<div class="table">
        <div class="row wt-header">
            <?php            
                
                 for ($x = 0; $x<count($daytimes); $x++){
                   echo '<div style="text-align: center;">'.$language_info['day_time'][$language][$x].'</div>';                    
                }                   
                
            ?>        
        </div>       
        <?php 
              
                $dayofweek = $language_info['days'][$language][date('w', strtotime("+"."0"." day"))];                               
                echo '<div class="row" id="wt-today+"'.'0'.'>
                        <div class="description">'.$dayofweek.' - '.date("Y-m-d", strtotime("+"."0"." day")).'</div>
           	            <div class="weather_detail">';
                foreach ($daytimes as $daytime){
                    $hourly_index = $daytime + 24 * 0;
                    $hourly_code =  $forecast_data["hourly"]["weather_code"][$hourly_index];
                    
                    echo '<div><img src="'
                        .$path.$weather_codes[$hourly_code]['day']['image'].'"><p>'
                        .round($forecast_data["hourly"]["temperature_2m"][$hourly_index], 0).'°C</p><p>'
                        .$weather_codes[$hourly_code]['day']['description'][$language].'</p></div>';
                    };
			    echo '</div>
                    </div>';
            
        ?>
    </div>
</div>