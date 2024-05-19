<?php
    require_once 'auth.php';

    if (!isset($_GET["category"])) {
        exit;
    }

    header('Content-Type: application/json');

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
    $category = mysqli_real_escape_string($conn, $_GET["category"]);
    $query = "SELECT * FROM collection WHERE category = '$category'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $resultArray = array();
    while($entry = mysqli_fetch_assoc($res)) {
        $resultArray[] = array('id' => $entry['id'], 'category' => $entry['category'], 'content' => json_decode($entry['content'])); 
    }
    echo json_encode($resultArray);
    mysqli_free_result($res);
    mysqli_close($conn);
?>