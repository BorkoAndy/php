<?php
header('Content-Type: application/json'); // Tell browser it's JSON

$apiKey = 'a78b8cd5-657a-4431-a075-b20cc7bd5102:fx';
$text = $_POST['text'] ?? '';
$targetLang = $_POST['target_lang'] ?? 'DE';

if (!$text) {
    echo json_encode(['error' => 'Missing text']);
    exit;
}

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

$response = file_get_contents($deeplUrl, false, stream_context_create($options));

if ($response === false) {
    echo json_encode(['error' => 'Translation failed']);
    exit;
}

echo $response; // DeepL already returns JSON
?>