<?php
#FETCH DA CHIAMARE NEL MOMENTO IN CUI VIENE CLICCATO IL CUORE, PER METTERE LIKE O RIMUOVERLO 

require_once 'auth.php';
//se non sono loggta nel momento in cui provo a mettere like mi manda al login
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
    $query_check = "SELECT * FROM favorites WHERE id_collection=$id_collection AND id_user=$userid";
    $res = mysqli_query($conn, $query_check) or die(mysqli_error($conn));
    //se trova risultati vuol dire che l'utente aveva gia messo like, quindi lo rimuove
    //se non trova risultati aggiunge il like
    if(mysqli_num_rows($res) > 0){
        $query_unlike = "DELETE FROM favorites WHERE id_collection=$id_collection AND id_user=$userid";
        $res = mysqli_query($conn, $query_unlike) or die(mysqli_error($conn));
        $img = "images/like.png";
    } else {
        $current_date = date('Y-m-d');
        $query_like = "INSERT INTO favorites (id_user, id_collection, time) VALUES($userid, $id_collection, '$current_date')";
        $res = mysqli_query($conn, $query_like) or die(mysqli_error($conn));
        $img = "images/unlike.png";
    }

    //vediamo quanti like ha quell'opera
    $query_count = "SELECT COUNT(*) FROM favorites WHERE id_collection=$id_collection";
    $res = mysqli_query($conn, $query_count) or die(mysqli_error($conn));
    $num = mysqli_fetch_assoc($res);

    
    //gli faccio ritornare l'immagine del cuore e il num di likes aggiornato
    $result[] = array ('res' => true, 'id' => $id_collection, 'img' => $img , 'num_like' => $num);
    echo json_encode($result);
    mysqli_free_result($res);
}
mysqli_close($conn);
exit;    
?>

