<?php
#DA RICHIAMARE IN PERSONAL_AREA.JS PER MOSTRARE LE NOTIFICHE, E AL CLICK DI HIDE ALL IN PERSONAL_AREA.JS con get=true PER ELIMINARE LE NOTIFICHE
require_once 'auth.php';

if (!$userid = checkAuth()) {
    header("Location: login.php");
    exit;
}

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
$resultArray = array();
$userid = mysqli_real_escape_string($conn, $userid);

if(isset($_GET['hide'])){
    $query = "DELETE FROM notifications where user_id=$userid";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    if($res) {
        $query = "SELECT * FROM notifications where user_id=$userid";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        while($entry = mysqli_fetch_assoc($res)) {
            $resultArray[] = array('id' => $entry['id'], 'content' => $entry['content']); 
        }
        echo json_encode($resultArray);
        mysqli_free_result($res);
        mysqli_close($conn);
        exit;
        
    }

} else{
    $query = "SELECT * FROM notifications where user_id=$userid";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    while($entry = mysqli_fetch_assoc($res)) {
        $resultArray[] = array('id' => $entry['id'], 'content' => $entry['content']); 
    }
    echo json_encode($resultArray);
    mysqli_free_result($res);
    mysqli_close($conn);
    exit;
}


?>