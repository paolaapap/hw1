<?php
require_once 'auth.php';

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
$query = "SELECT * FROM sponsor";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

$sponsorArray = array();
while($entry = mysqli_fetch_assoc($res)) {
    $sponsorArray[] = array('id' => $entry['id'], 'content' => json_decode($entry['content']));
}
echo json_encode($sponsorArray);
mysqli_free_result($res);
mysqli_close($conn);
exit;
?>