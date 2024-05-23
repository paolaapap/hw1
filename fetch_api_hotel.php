<?php
require_once 'api_credentials.php';
$latitude = "40.730610";
$longitude = "-73.935242";
$check_in_value = urlencode($_GET["check_in"]);
$check_out_value = urlencode($_GET["check_out"]);
$adults_value = urlencode($_GET["adults"]);
$rooms_value = urlencode($_GET["room"]);

$url = "https://tripadvisor16.p.rapidapi.com/api/v1/hotels/searchHotelsByLocation?latitude=$latitude&longitude=$longitude&checkIn=$check_in_value&checkOut=$check_out_value&pageNumber=1&adults=$adults_value&rooms=$rooms_value&currencyCode=USD";
// "https://tripadvisor16.p.rapidapi.com/api/v1/hotels/searchHotelsByLocation?latitude=40.730610&longitude=-73.935242&checkIn=2024-07-16&checkOut=2024-07-20&pageNumber=1&adults=3&rooms=1&currencyCode=USD"
$headers = array (
    "x-rapidapi-key: $api_key_hotel",
    "X-RapidAPI-Host: tripadvisor16.p.rapidapi.com"
);

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($curl);
curl_close($curl);
echo $response;
?>


