<?php
require_once 'auth.php';

if (!$userid = checkAuth()) {
    header("Location: login.php");
    exit;
} else {

    header('Content-Type: application/json');

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
    $resultArray = array();

    $userid = mysqli_real_escape_string($conn, $userid);
    $query = "SELECT DISTINCT a.* FROM auctions a JOIN offers o on a.id=o.auction_id WHERE o.user_id = $userid";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    while($entry = mysqli_fetch_assoc($res)) {
        $resultArray[] = array('asta_id' => $entry['id'], 'user_id' => $entry['user_id'], 'foto' => $entry['foto'], 'titolo' => $entry['titolo'],
                                'durata' => $entry['durata'], 'prezzo_iniziale' => $entry['prezzo_iniziale'], 'num_offerte' => $entry['num_offerte'], 'ultimo_prezzo' => $entry['ultimo_prezzo']); 
    }
    echo json_encode($resultArray);
    mysqli_free_result($res);
    mysqli_close($conn);
    exit;
}
?>