<?php
// Load auth.json
$authPath = './auth.json';
$authData = json_decode(file_get_contents($authPath), true);


// Get POST data
$domain = isset($_POST['domain']) ? $_POST['domain'] : '';
$key = isset($_POST['key']) ? $_POST['key'] : '';
$flags = isset($_POST['flags']) ? $_POST['flags'] : false;
$selected_list = isset($_POST['selected_list']) ? $_POST['selected_list'] : false;
$languageCodes = isset($_POST['languages']) ? explode(',', $_POST['languages']) : [];
$icon_set = isset($_POST['icon_set']) ? $_POST['icon_set'] : '';


$allLangs = [
  ['code' => 'de', 'label' => 'DE', 'flag' => 'de.png'],
  ['code' => 'en', 'label' => 'EN', 'flag' => 'gb.png'],
  ['code' => 'hu', 'label' => 'HU', 'flag' => 'hu.png'],
  ['code' => 'it', 'label' => 'IT', 'flag' => 'it.png'],
  ['code' => 'nl', 'label' => 'NL', 'flag' => 'nl.png'],
  ['code' => 'pl', 'label' => 'PL', 'flag' => 'pl.png'],
  ['code' => 'sk', 'label' => 'SK', 'flag' => 'sk.png'],
  ['code' => 'sl', 'label' => 'SL', 'flag' => 'sl.png'],
  ['code' => 'fi', 'label' => 'FI', 'flag' => 'fi.png'],
  ['code' => 'fr', 'label' => 'FR', 'flag' => 'fr.png'],
  ['code' => 'hr', 'label' => 'HR', 'flag' => 'hr.png'],
  ['code' => 'cs', 'label' => 'CS', 'flag' => 'cs.png'],
  ['code' => 'ru', 'label' => 'RU', 'flag' => 'ru.png'],
  ['code' => 'ua', 'label' => 'UA', 'flag' => 'ua.png']
];


// Filter based on requested languages
$langs = array_filter($allLangs, function ($lang) use ($languageCodes) {
  return in_array($lang['code'], $languageCodes);
});

// Check authorization
$authorized = false;
foreach ($authData as $entry) {
  if ($entry['name'] === $domain && $entry['key'] === $key) {
    $authorized = true;
    break;
  }
}
// Append to log file
// $logEntry = date('Y-m-d H:i:s') . " | Referer: " . $langs . PHP_EOL;
// file_put_contents('./debug.log', $logEntry, FILE_APPEND);

if ($authorized) {
  // Respond with JS and flag paths
  echo '<div id="translator-container"></div>';
  echo '<script>
          window.translatorConfig = {
            key: "' . htmlspecialchars($key) . '",
            icon_set: "' . htmlspecialchars($icon_set) . '",
            mode: "' . ($flags ? 'flags' : 'labels') . '",
            layout: "' . ($selected_list ? 'select' : 'row') . '",
            langs: ' . json_encode(array_values($langs)) . '
          };
        </script>';
  echo '<script src="https://www.netcontact.at/API/Translate/translator.js"></script>';
  echo '<link rel="stylesheet" href="https://www.netcontact.at/API/Translate/translator.css">';
} else {
  echo '<div style="color:red;">❌ Unauthorized access</div>';
}
