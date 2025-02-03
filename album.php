<?php
$accessToken = '644fe169cf0c2ca2efd2f35001a47fa1322d07d0';
$clientId = 'eab4cc6f86173ce';
$images = ['img1.png', 'img2.png', 'img3.png'];


function createImgurImage($imagePath, $clientId) {
    if (!file_exists($imagePath)) {
        echo "Error: File $imagePath not found!<br>";
        return '';
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
        if (isset($data['data']['id'])) {
            echo "Image uploaded successfully: " . $data['data']['id'] . "<br>";
            return $data['data']['id'];
        } else {
            echo "Error: Unexpected response format.<br>";
        }
    } else {
        echo "Error uploading file $imagePath: $response <br>";
    }
    return '';
}


function createImgurAlbum($images, $accessToken, $clientId) {
    $curl = curl_init();
    $imageHashes = [];
    foreach ($images as $image) {
        $imageHashes[] = createImgurImage($image, $clientId);
    }
    var_dump($imageHashes); // Debug information

    $postFields = array(
        'ids[]' => $imageHashes,
        'title' => 'cos',
        'description' => 'cos',
        'cover' => $imageHashes[0]
    );

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.imgur.com/3/album',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $accessToken
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $responseDecoded = json_decode($response, true);

    if (isset($responseDecoded['data']['id'])) {
        echo "Album created successfully: " . $responseDecoded['data']['id'] . "<br>";
        return $responseDecoded['data']['id'];
    } else {
        echo "Error creating album: " . (isset($responseDecoded['data']['error']) ? $responseDecoded['data']['error'] : 'Unknown error') . "<br>";
        return '';
    }
}
$albumHash = createImgurAlbum($images, $accessToken, $clientId);

if (strpos($albumHash, 'Error') === false) {
    $imageHash1 = createImgurImage('img1.png', $clientId);
    $imageHash2 = createImgurImage('img2.png', $clientId);
    var_dump($imageHash1, $imageHash2); // Debug information

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.imgur.com/3/album/' . $albumHash . '/add',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('ids[]' => $imageHash1, 'ids[]' => $imageHash2),
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $accessToken
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo 'Album Link: https://imgur.com/a/' . $albumHash;
    echo '<br>' . $response;
} else {
    echo $albumHash; // Display error message if album creation failed
}
