<?php
#DA RICHIAMARE IN OGNI FILE PHP CHE RIGUARDA LE ASTE. CONTROLLA SE L'ASTA E' SCADUTA, SE LO E' DECRETA IL VINCITORE
require_once 'auth.php';
header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
$result = false;

$query = "SELECT * FROM auctions";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

while($entry = mysqli_fetch_assoc($res)) {

    $dbDatetime = $entry['durata'];
    $currentDatetime = new DateTime();
    $dbDatetime = new DateTime($dbDatetime);

    if ($currentDatetime > $dbDatetime) { //se è scaduta  

        $auction_expired = mysqli_real_escape_string($conn, $entry['id']);
        //decreto il vincitore
        $query_winner = "SELECT * FROM offers WHERE auction_id=$auction_expired ORDER BY prezzo DESC LIMIT 1" ;
        $res2 = mysqli_query($conn, $query_winner) or die(mysqli_error($conn));
        $entry2 = mysqli_fetch_assoc($res2);
        if(mysqli_num_rows($res2) > 0){
            //metto nella tab notifications la notifica x il vincitore
            $userid = $entry2['user_id'];
            $userid = mysqli_real_escape_string($conn, $userid);
            $title = $entry['titolo'];
            $content = "You have won the auction of " . $title;
            $query_notification = "INSERT INTO notifications (user_id, content) VALUES ($userid, '$content')";
            $res2 = mysqli_query($conn, $query_notification) or die(mysqli_error($conn));
        }
        //la elimino dalla tab auctions
        $query_remove = "DELETE FROM auctions WHERE id=$auction_expired";
        $res2 = mysqli_query($conn, $query_remove) or die(mysqli_error($conn));
        //elimino la foto dalla cartella 
        $path = $entry['foto'];
        unlink($path);
        $result=true;
    } 
}

echo json_encode(array('ok' => $result));
mysqli_free_result($res);
mysqli_close($conn);
exit;

?>