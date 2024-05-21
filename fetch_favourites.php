<?php
#FETCH DA CHIAMARE OGNI VOLTA CHE SI APRE PERSONAL AREA

require_once 'auth.php';
//se non sono loggta
if (!$userid = checkAuth()) {
    $result[] = array ('res' => false);
    echo json_encode($result);
    exit;
}

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

$result = array();
$userid = mysqli_real_escape_string($conn, $userid);
$query= "SELECT c.* FROM (favorites f JOIN users u ON f.user_id=u.id) JOIN collections c on c.id=f.collection_id WHERE f.user_id=$userid";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

while($entry = mysqli_fetch_assoc($res)) {
    $result[] = array('res' => true, 'id' => $entry['id'], 'content' => json_decode($entry['content']));
}
echo json_encode($result);
mysqli_free_result($res);
mysqli_close($conn);
exit;    
?>