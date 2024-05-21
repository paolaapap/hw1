<?php
#FETCH DA CHIAMARE SEMPRE AL CARICAMENTO DELLA PAGINA, VIENE CHIAMATA PER OGNI OPERA E CON GET PASSO L'INDEX DELL'OPERA
require_once 'auth.php';

if (isset($_GET["id_collection"])){

    header('Content-Type: application/json');

    //fa la query per vedere il numero di like
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
    $result = array();
    $id_collection = mysqli_real_escape_string($conn, $_GET["id_collection"]);
    $query_count = "SELECT COUNT(*) FROM favorites WHERE collection_id=$id_collection";
    $res = mysqli_query($conn, $query_count) or die(mysqli_error($conn));
    $num = mysqli_fetch_assoc($res);

    //se non sono loggta mette il cuore semplice per tutti, se sono loggata mi torna il cuore nero
    if (!$userid = checkAuth()) {
        $img = "images/like.png";
    } else {
    //se sono loggata controlla se ho messo like, se l'ho messo mi da il cuore nero
        $query_check = "SELECT * FROM favorites WHERE collection_id=$id_collection AND user_id=$userid";
        $res = mysqli_query($conn, $query_check) or die(mysqli_error($conn));
        //se trova risultati vuol dire che l'utente aveva gia messo like
        if(mysqli_num_rows($res) > 0){
            $img = "images/unlike.png";
        } else {
            $img = "images/like.png";
        }
    }

    $result[] = array ('id' => $id_collection, 'img' => $img , 'num_like' => $num);
    echo json_encode($result);
    mysqli_free_result($res);
    mysqli_close($conn);
    exit; 
}
?>

