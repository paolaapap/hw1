<?php
    require_once 'auth.php';

    session_destroy();

    if(isset($_COOKIE['user_id']) && isset($_COOKIE['token']) && isset($_COOKIE['cookie_id'])){
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: " .mysqli_connect_error());
        $cookieid = mysqli_real_escape_string($conn, $_COOKIE['cookie_id']);
        $userid = mysqli_real_escape_string($conn, $_COOKIE['user_id']);
        $res = mysqli_query($conn, "SELECT id, hash FROM cookies WHERE id = $cookieid AND user_id = $userid");
        if($cookie=mysqli_fetch_assoc($res)){
          if (password_verify(($_COOKIE['token']), $cookie['hash'])){ //verifico che il token sia ancora valido perche altrimenti sara gia stato eliminato da auth.php
                mysqli_query($conn, "DELETE FROM cookies WHERE id = ".$cookie['id']) or die("Errore: " .mysqli_error($conn));
                mysqli_close($conn);
                setcookie("user_id", "");
                setcookie("cookie_id", "");
                setcookie("token", "");
            }
        }
    }
    header("Location: index.php");
    exit;
?>