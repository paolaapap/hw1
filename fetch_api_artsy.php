<?php
require_once 'api_credentials.php';
    
// ACCESS TOKEN

$url_token = "https://api.artsy.net/api/tokens/xapp_token?client_id=$client_id_artsy&client_secret=$client_secret_artsy";

$curl = curl_init($url_token);
curl_setopt($curl, CURLOPT_URL, $url_token);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


$token=json_decode(curl_exec($curl), true);
curl_close($curl);

//QUERY EFFETTIVA

if(isset($_GET["artist_name"])){
$url = "https://api.artsy.net/api/artists/";
$name=$_GET["artist_name"];
$name = strtolower($name);
$name = str_replace(' ', '-', $name); // Sostituisci gli spazi con trattini
    
$url = "https://api.artsy.net/api/artists/$name";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Xapp-Token:'. $token['token'])
);

$response = curl_exec($ch);
curl_close($ch);
echo $response;
} else if(isset($_GET["artist_id"])){
    $url = "https://api.artsy.net/api/artists/";
    $id=$_GET["artist_id"];
   
    $url = "https://api.artsy.net/api/artworks?artist_id=$id";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-Xapp-Token:'. $token['token'])
    );

    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    
}
?>
