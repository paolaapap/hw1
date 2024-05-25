<?php
require_once 'auth.php';
require_once 'fetch_check_expires.php';

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
$resultArray = array();

#SE VOGLIO INFO SU UNA SPECIFICA ASTA 
 if (isset($_GET["id"])) {   

    $id = mysqli_real_escape_string($conn, $_GET["id"]);
    $query = "SELECT * FROM auctions WHERE id = $id";

 } 
 #SE VOGLIO LE ASTE CREATE DALL'UTENTE
 else if(isset($_GET["user_id"])){
//AGGIUNGI CONTROLLO SULL'AUTENTICAZIONE
    if ($userid = checkAuth()) {

        $userid = mysqli_real_escape_string($conn, $_SESSION["user_id"]);
        $query = "SELECT * FROM auctions WHERE user_id = $userid";

    } else {exit;}
 } 
 #SE VOGLIO TUTTE LE ASTE IN CORSO 
 else {

    $query = "SELECT * FROM auctions";

 }
   
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));
while($entry = mysqli_fetch_assoc($res)) {
    $resultArray[] = array('asta_id' => $entry['id'], 'user_id' => $entry['user_id'], 'foto' => $entry['foto'], 'titolo' => $entry['titolo'],
                            'durata' => $entry['durata'], 'prezzo_iniziale' => $entry['prezzo_iniziale'], 'num_offerte' => $entry['num_offerte'], 'ultimo_prezzo' => $entry['ultimo_prezzo']); 
}
echo json_encode($resultArray);
mysqli_free_result($res);
mysqli_close($conn);
exit;
?>