<?php
require_once 'auth.php';

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
$query = "SELECT * FROM collection";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

$collectionArray = array();
while($entry = mysqli_fetch_assoc($res)) {
    $collectionArray[] = array('id' => $entry['id'], 'category' => $entry['category'], 'content' => json_decode($entry['content']));
}
echo json_encode($collectionArray);
mysqli_free_result($res);
mysqli_close($conn);
exit;
?>