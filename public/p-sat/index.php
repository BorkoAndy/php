<?php
    $query = "SELECT ARTIKEL_ARTNR, ARTIKEL_BEZ1 FROM ARTIKEL";
    $data = ["query" => $query];
    $jsonData = json_encode($data);
    $counter = 0;
    $ch = curl_init("http://localhost:5101/artikel");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);

    $response = curl_exec($ch);    
    $results = json_decode($response, true);    
    
    foreach ($results as $row) {
        // print_r($row['ARTIKEL_ARTNR']);
        $query = "SELECT ARTPREIS_PREIS FROM ARTPREIS WHERE ARTPREIS_ARTNR = '{$row['ARTIKEL_ARTNR']}'";
        $data = ["query" => $query];
        $jsonData = json_encode($data);
        $ch1 = curl_init("http://localhost:5101/artikel");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_POST, true);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);
        
        $response = curl_exec($ch1);    
        $results2 = json_decode($response, true);
        foreach ($results2 as $row2) {
            if ($row2['ARTPREIS_PREIS'] != ''){
                echo "<pre>";
               print_r($row['ARTIKEL_ARTNR'] . "\t" . $row['ARTIKEL_BEZ1'] . "\t" . $row2['ARTPREIS_PREIS']);
               echo "</pre>";
               $counter++;
               break;
            }
        }
        
       
        
        
        
        // echo "<pre>" . print_r($row, true) . "</pre>";
    }
    
    echo $counter;
    
    curl_close($ch);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>