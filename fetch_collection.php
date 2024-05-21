<?php
require_once 'auth.php';

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

 if (isset($_GET["category"])) {       
    $category = mysqli_real_escape_string($conn, $_GET["category"]);
    $query = "SELECT * FROM collections WHERE category = '$category'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $resultArray = array();
    while($entry = mysqli_fetch_assoc($res)) {
        $resultArray[] = array('id' => $entry['id'], 'category' => $entry['category'], 'content' => json_decode($entry['content'])); 
    }
    echo json_encode($resultArray);
    mysqli_free_result($res);
    mysqli_close($conn);
    exit;

 } else if(isset($_GET["id"])){
    $id = mysqli_real_escape_string($conn, $_GET["id"]);
    $query = "SELECT * FROM collections WHERE id=$id";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $collectionArray = array();
    while($entry = mysqli_fetch_assoc($res)) {
        $collectionArray[] = array('id' => $entry['id'], 'num_like' => $entry['num_like'], 'category' => $entry['category'], 'content' => json_decode($entry['content']));
    }
    echo json_encode($collectionArray);
    mysqli_free_result($res);
    mysqli_close($conn);
    exit;

 } else {
    $query = "SELECT * FROM collections";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $collectionArray = array();
    while($entry = mysqli_fetch_assoc($res)) {
        $collectionArray[] = array('id' => $entry['id'], 'category' => $entry['category'], 'content' => json_decode($entry['content']));
    }
    echo json_encode($collectionArray);
    mysqli_free_result($res);
    mysqli_close($conn);
    exit;
 }
?>