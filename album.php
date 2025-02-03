<?php

function createImgurAlbum($accessToken, $imageHashes) {
    $curl = curl_init();
    
    // Prepare the POST fields with the image hashes
    $postFields = array('ids[]' => $imageHashes);
    
    // Optional parameters for album (e.g., title, description, cover)
    $postFields['title'] = 'My dank meme album';
    $postFields['description'] = 'This album contains a lot of dank memes. Be prepared.';
    $postFields['cover'] = $imageHashes[0]; // Set the cover image to the first image in the array
    
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

    // Execute the request
    $response = curl_exec($curl);

    // Close the cURL session
    curl_close($curl);

    // Decode the response
    $responseDecoded = json_decode($response, true);

    // Check if the album was created successfully
    if (isset($responseDecoded['data']['id'])) {
        // Return the link to the album
        return 'https://imgur.com/a/' . $responseDecoded['data']['id'];
    } else {
        // Handle error (e.g., return an error message)
        return 'Error: ' . $responseDecoded['data']['error'];
    }
}

// Example usage
$accessToken = '644fe169cf0c2ca2efd2f35001a47fa1322d07d0';
$imageHashes = ['img1.png', 'img2.png', 'img3.png']; // Replace with actual image hashes

$albumLink = createImgurAlbum($accessToken, $imageHashes);
echo 'Album Link: ' . $albumLink;
?>
