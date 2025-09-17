<?php
$currentDomain = $_SERVER['HTTP_HOST'];
$scriptKey = 'b8zWThA1p10IlFWo0WPcII9YaDarfe42tXkrh4jcX1B8L8hQbgkZHv5TDlvtMowi'; // You can set this dynamically
$useFlags = true;      // or false for label mode

// Prepare POST data
$postData = [
  'domain' => $currentDomain,
  'key' => $scriptKey,
  'flags' => $useFlags
];

// Send POST to Server B
$ch = curl_init('https://www.netcontact.at/API/Translate/authorize.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
$response = curl_exec($ch);
curl_close($ch);

// Output the response (JS + HTML)
// echo $response;
?>
