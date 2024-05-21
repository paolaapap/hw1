<?php

    require_once 'dbconfig.php';
    session_start();
    function checkAuth() {
        global $dbconfig;
        if(!isset($_SESSION['user_id'])){
            if(isset($_COOKIE['user_id']) && isset($_COOKIE['token']) && isset($_COOKIE['cookie_id'])){
                $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: " .mysqli_connect_error());
                $cookieid = mysqli_real_escape_string($conn, $_COOKIE['cookie_id']);
                $userid = mysqli_real_escape_string($conn, $_COOKIE['user_id']);
                $res = mysqli_query($conn, "SELECT * FROM cookies WHERE id = $cookieid AND user_id = $userid");
                if($cookie=mysqli_fetch_assoc($res)){
                    if(time() > $cookie['expires']){
                        mysqli_query($conn, "DELETE FROM cookies WHERE id = ".$cookie['id']) or die("Errore: " .mysqli_error($conn));
                        header("Location: logout.php");
                        exit;
                    } else if (password_verify(($_COOKIE['token']), $cookie['hash'])){
                        $_SESSION['user_id'] = $_COOKIE['user_id'];
                        mysqli_close($conn);
                        return $_SESSION['user_id'];
                    }
                }
            }
        } else {
            return $_SESSION['user_id'];
        }
    }
?>