<?php
// ✅ CORS headers to allow cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// ✅ Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// ✅ Suppress warnings and errors from breaking JSON
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_PARSE);

// ✅ DeepL API key (replace with your actual key)
$apiKey = 'a78b8cd5-657a-4431-a075-b20cc7bd5102:fx';

// ✅ Get POST parameters
$text = isset($_POST['text']) ? $_POST['text'] : '';
$targetLang = isset($_POST['target_lang']) ? $_POST['target_lang'] : 'EN';

// ✅ Validate input
if (!$text) {
    echo json_encode(['error' => 'Missing text']);
    exit;
}

// ✅ Prepare DeepL API request
$deeplUrl = 'https://api-free.deepl.com/v2/translate';
$postData = http_build_query([
    'auth_key' => $apiKey,
    'text' => $text,
    'target_lang' => $targetLang
]);

$options = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded",
        'content' => $postData
    ]
];

// ✅ Send request to DeepL
$response = @file_get_contents($deeplUrl, false, stream_context_create($options));

// ✅ Handle failure
if ($response === false) {
    $error = error_get_last();
    $result =  json_encode([
        'error' => 'DeepL API call failed',
        'debug' => $error['message']
    ]);
    file_put_contents('debug.log', print_r($result, true));
    exit;
}

// ✅ Return DeepL's JSON response directly
echo $response;
