<?php
require_once 'auth.php';

header('Content-Type: application/json');


if (!$userid = checkAuth()) {
    echo json_encode(array('ok' => false, 'error' => 'Utente non autenticato'));
    exit;
}

if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['img']) && isset($_POST['link'])) {

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $image = mysqli_real_escape_string($conn, $_POST['img']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    $userid = mysqli_real_escape_string($conn, $_SESSION['user_id']);

    // Verifica se il tour è già nei preferiti
    $query_check = "SELECT * FROM tours WHERE user_id = $userid AND JSON_UNQUOTE(JSON_EXTRACT(content, '$.image')) = '$image'"; //json_unquote è per rimuovere le virgolette 
    $res = mysqli_query($conn, $query_check)  or die("Errore: ". mysqli_connect_error());

    if (mysqli_num_rows($res) > 0) {
        echo json_encode(array('ok' => true, 'message' => 'Il tour è già nei preferiti'));
        mysqli_free_result($res);
        mysqli_close($conn);
        exit;
    }

    $result = array();
    // Altrimenti inserisco il nuovo tour nei preferiti
    $query = "INSERT INTO tours (user_id, tour_id, content) VALUES ('$userid', '$id', JSON_OBJECT('title', '$title', 'image', '$image', 'link', '$link'))";
    $res = mysqli_query($conn, $query) or die("Errore: ". mysqli_connect_error());

    if ($res) {
        $result['ok'] = true;
    } else {
        $result['ok'] = false;
        $result['error'] = 'Errore durante il salvataggio';
    }

    echo json_encode($result);
    mysqli_close($conn);
    exit;
} 
?>
