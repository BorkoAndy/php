<?php
$currentDomain = $_SERVER['HTTP_HOST'];
$scriptKey = 'b8zWThA1p10IlFWo0WPcII9YaDarfe42tXkrh4jcX1B8L8hQbgkZHv5TDlvtMowi'; // You can set this dynamically
$useFlags = true;      // true for flags or false for label mode
$selected_list = false; // true = use selected list of options or false = row
$languages = ['en', 'de', 'fr', 'nl', 'it', 'ru']; //Language codes
$icon_set = 'w40'; // '' for waved icons; 'w40' for same width original icons

$postData = [
  'domain' => $currentDomain,
  'key' => $scriptKey,
  'flags' => $useFlags,
  'selected_list' => $selected_list,
  'languages' => implode(',', $languages), // Send as comma-separated string
  'icon_set' => $icon_set //set icon collection (path to icon files)
];

// Send POST to Server B
$ch = curl_init('https://www.netcontact.at/API/Translate/authorize.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
$response = curl_exec($ch);
curl_close($ch);

// Output the response (JS + HTML)
echo $response;
?>
