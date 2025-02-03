<?php

$clientId = 'eab4cc6f86173ce';
$imagePaths = ['img1.png', 'img2.jpg', 'img3.png'];

foreach ($imagePaths as $imagePath) {
    if (!file_exists($imagePath)) {
        echo "Ошибка: Файл $imagePath не найден!<br>";
        continue;
    }

    $imageData = base64_encode(file_get_contents($imagePath));

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.imgur.com/3/image',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => array(
            'image' => $imageData
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Client-ID $clientId"
        ),
    ));

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if ($http_code == 200) {
        $data = json_decode($response, true);
        echo "Файл $imagePath загружен: <a href='".$data['data']['link']."'>".$data['data']['link']."</a><br>";
    } else {
        echo "Ошибка загрузки файла $imagePath: $response <br>";
    }
}
?>
