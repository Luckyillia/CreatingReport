<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.imgur.com/oauth2/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('refresh_token' => '{{refreshToken}}','client_id' => '{{eab4cc6f86173ce}}','client_secret' => '{{7b6e464a9bc26e6544c2b973e361eff0326a6a65}}','grant_type' => 'refresh_token'),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;