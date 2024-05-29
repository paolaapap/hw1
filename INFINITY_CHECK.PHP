<?php
//all'esame simulo la chiamata a questo file andandolo a chiamare tramite browser
//nella realtà è possibile creare un file batch che avvia XAMPP e questo codice PHP in automatico -> settando un task pianificato su windows

/*@echo off
//Avvio XAMPP
start "" "C:\xampp\xampp_start.exe"
//Attendo un po' di tempo per assicurarmi che XAMPP sia avviato
timeout /t 10 /nobreak

//Avvio lo script PHP
cd C:\xampp\php
php C:\xampp\htdocs\h1\INFINITY_CHECK.php
// Disabilita il limite di tempo di esecuzione
set_time_limit(0); */
require_once 'auth.php';

while (true) {

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
    $query = "SELECT * FROM auctions";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    while($entry = mysqli_fetch_assoc($res)) {

        $dbDatetime = $entry['durata'];
        $currentDatetime = new DateTime(); //oggetto DateTime per la data e l'ora corrente
        $dbDatetime = new DateTime($dbDatetime); //oggetto DateTime per la data e l'ora di scadenza dell'asta

        if ($currentDatetime > $dbDatetime) { //se è scaduta  

            $auction_expired = mysqli_real_escape_string($conn, $entry['id']);
            //decreto il vincitore
            $query_winner = "SELECT * FROM offers WHERE auction_id=$auction_expired ORDER BY prezzo DESC LIMIT 1" ;
            $res2 = mysqli_query($conn, $query_winner) or die(mysqli_error($conn));
            $entry2 = mysqli_fetch_assoc($res2);
            if(mysqli_num_rows($res2) > 0){
                //metto nella tab notifications la notifica x il vincitore
                $userid = $entry2['user_id'];
                $userid = mysqli_real_escape_string($conn, $userid);
                $title = $entry['titolo'];
                $content = "You have won the auction of " . $title;
                $query_notification = "INSERT INTO notifications (user_id, content) VALUES ($userid, '$content')";
                $res2 = mysqli_query($conn, $query_notification) or die(mysqli_error($conn));
            }
            //la elimino dalla tab auctions
            $query_remove = "DELETE FROM auctions WHERE id=$auction_expired";
            $res2 = mysqli_query($conn, $query_remove) or die(mysqli_error($conn));
            //elimino la foto dalla cartella 
            $path = $entry['foto'];
            unlink($path);
        } 
    }

    //faccio il check ogni minuto
    sleep(60);
}
?>