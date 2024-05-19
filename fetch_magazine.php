<?php
require_once 'auth.php';

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
$query = "SELECT * FROM magazine";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

$magazineArray = array();
while($entry = mysqli_fetch_assoc($res)) {
    $magazineArray[] = array('id' => $entry['id'], 'content' => json_decode($entry['content']));
}
echo json_encode($magazineArray);
mysqli_free_result($res);
mysqli_close($conn);
exit;
?>