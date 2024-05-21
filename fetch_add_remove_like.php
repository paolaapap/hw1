<?php
#FETCH DA CHIAMARE NEL MOMENTO IN CUI VIENE CLICCATO IL CUORE, PER METTERE LIKE O RIMUOVERLO 

require_once 'auth.php';
//se non sono loggata nel momento in cui provo a mettere like mi manda al login
if (!$userid = checkAuth()) {
    $result[] = array ('res' => false);
    echo json_encode($result);
    //header("Location: login.php");
    exit;
}

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

 if (isset($_GET["id_collection"])) {       

    $result = array();

    $id_collection = mysqli_real_escape_string($conn, $_GET["id_collection"]);
    $query_check = "SELECT * FROM favorites WHERE collection_id=$id_collection AND user_id=$userid";
    $res = mysqli_query($conn, $query_check) or die(mysqli_error($conn));
    //se trova risultati vuol dire che l'utente aveva gia messo like, quindi lo rimuove
    //se non trova risultati aggiunge il like
    if(mysqli_num_rows($res) > 0){
        $query_unlike = "DELETE FROM favorites WHERE collection_id=$id_collection AND user_id=$userid";
        $res = mysqli_query($conn, $query_unlike) or die(mysqli_error($conn));
        $img = "images/like.png";
    } else {
        $query_like = "INSERT INTO favorites (user_id, collection_id) VALUES($userid, $id_collection)";
        $res = mysqli_query($conn, $query_like) or die(mysqli_error($conn));
        $img = "images/unlike.png";
    }
    
    //gli faccio ritornare l'immagine del cuore e il num di likes aggiornato
    $result[] = array ('res' => true, 'id' => $id_collection, 'img' => $img);
    echo json_encode($result);
    //mysqli_free_result($res);
}
mysqli_close($conn);
exit;    
?>

