<?php
require_once 'auth.php';

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

 if (isset($_GET["category"])) {       
    $category = mysqli_real_escape_string($conn, $_GET["category"]);
    $query = "SELECT * FROM collection WHERE category = '$category'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $resultArray = array();
    while($entry = mysqli_fetch_assoc($res)) {
        $resultArray[] = array('id' => $entry['id'], 'category' => $entry['category'], 'content' => json_decode($entry['content'])); 
    }
    echo json_encode($resultArray);
    mysqli_free_result($res);
 } else {
    $query = "SELECT * FROM collection";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $collectionArray = array();
    while($entry = mysqli_fetch_assoc($res)) {
        $collectionArray[] = array('id' => $entry['id'], 'category' => $entry['category'], 'content' => json_decode($entry['content']));
    }
    echo json_encode($collectionArray);
    mysqli_free_result($res);
 }
mysqli_close($conn);
exit;
?>