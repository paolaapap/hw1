<?php
require_once 'api_credentials.php';


$url = "https://joj-image-search.p.rapidapi.com/v2/?q=moma%20tour&hl=en";
$headers = array (
    "x-rapidapi-key: $api_key_joj",
    "X-RapidAPI-Host: joj-image-search.p.rapidapi.com"
);

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($curl);
curl_close($curl);
echo $response;
?>