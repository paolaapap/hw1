<?php
require_once 'auth.php';

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
$query = "SELECT DISTINCT category FROM collection";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

$categoryArray = array();
while($entry = mysqli_fetch_assoc($res)) {
    $categoryArray[] = array('category' => $entry['category']); 
}
echo json_encode($categoryArray);
mysqli_free_result($res);
mysqli_close($conn);

?>