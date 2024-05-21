<?php
require_once 'auth.php';

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

 if (isset($_GET["section"])) {       

    $section = mysqli_real_escape_string($conn, $_GET["section"]);
    $query = "SELECT * FROM images WHERE section='$section'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $resultsArray = array();
    while($entry = mysqli_fetch_assoc($res)) {
        $resultsArray[] = array('id' => $entry['id'], 'content' => json_decode($entry['content']));
    }
    echo json_encode($resultsArray);
    mysqli_free_result($res);
    mysqli_close($conn);
    exit;
 }
?>