<?php
// Establish database connection
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <!-- {% block title %}        -->
        
    </title>
    <!-- {% block head_import %} -->
   
    <link rel="stylesheet" href="static/css/base_styles.css">    
</head>
<body class="oswald-font">
    <div id="header">
        <div id="small_logo">
            <!-- {% block small_logo %} -->
            <img src="st" alt="">
            
        </div>
        <div class="" id="room_description">
            <div class="field_title">Car</div>
            <div class="counter">Model</div>
        </div>
        <div class="count" id="from_date">
            <div class="field_title">from</div>
            <div class="counter">0</div>
        </div>
        <div class="count" id="till_date">
            <div class="field_title">till</div>
            <div class="counter">0</div>
        </div>
        <div class="count" id="guests_count">
            <div class="field_title">Hours</div>
            <div class="counter">0</div>
        </div>
        <div class="count" id="price_count">
            <div class="field_title">Price</div>
            <div class="counter">0</div>
        </div>
    </div>
    <div id="container">
        <div id="left">Image</div>
        <div id="center"> <!--should include all other files-->  
            <!-- {% block content %} -->
            <h2>Choose category</h2>

            
            
                   
                
                             
            </form>             
        </div>
        <div id="right">Image</div>
    </div> 
    <div id="footer">
        <div id="left-footer">Kontakt</div>
        <div id="center-footer">
            <!-- {% block footer_logo %} -->
            
            
        </div>
        <div id="right-footer">Copyright</div>
    </div>
</body>
</html>