<?php
require_once 'auth.php';

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

if (!$userid = checkAuth()) {
    echo json_encode(array('ok' => false, 'error' => 'Utente non autenticato'));
    exit;
}

$userid = mysqli_real_escape_string($conn, $userid);

if (isset($_GET['id_remove'])) {

    $tourid = mysqli_real_escape_string($conn, $_GET['id_remove']);

    $query_remove = "DELETE FROM tours WHERE tour_id = $tourid and user_id = $userid"; 
    $res = mysqli_query($conn, $query_remove)  or die("Errore: ". mysqli_connect_error());

    if ($res) {
        echo json_encode(array('ok' => true, 'message' => 'Il tour è stato rimosso dai preferiti'));
        mysqli_close($conn);
        exit;
    }
} 

$result = array();
// Altrimenti mi seleziono tutti i tour di quell'utente
$query = "SELECT * FROM tours WHERE user_id = $userid";
$res = mysqli_query($conn, $query) or die("Errore: ". mysqli_connect_error());

while($entry = mysqli_fetch_assoc($res)) {
    $result[] = array('tour_id' => $entry['tour_id'], 'content' => json_decode($entry['content']));
}

echo json_encode($result);
mysqli_free_result($res);
mysqli_close($conn);
exit;

?>

