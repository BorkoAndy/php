<?php

echo "HI!";


// Initialize cURL
$curl = curl_init();

// Set cURL options
curl_setopt_array($curl, [
    CURLOPT_URL => "https://andywebapi.azurewebsites.net/weatherforecast",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Accept: application/json",
        "Content-Type: application/json"
    ]
]);

// Execute request
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Close cURL
curl_close($curl);

// Check for errors
if ($response === false || $httpCode !== 200) {
    die("API request failed");
}

// Decode JSON
$data = json_decode($response, true);

// Use the data
print_r($data);

?>