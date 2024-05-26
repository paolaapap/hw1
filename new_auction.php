<?php
require_once 'auth.php';

if (!$userid = checkAuth()) {
    header("Location: login.php");
    exit;
}

if(isset($_FILES["uploaded_image"]["name"]) && isset($_POST['title']) && isset($_POST['deadline']) && isset($_POST['starting_price'])){
    $error = array();
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: ". mysqli_connect_error());
    
    #VALIDAZIONE DEADLINE
    if(!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $_POST['deadline'])){
      $error[] = "Invalid deadline format.";
    }

    #VALIDAZIONE DEADLINE (non accetta: 0, -1, 01, abc, tre cifre decimali dopo la virgola)
    if(!preg_match('/^(?!0\d)\d*(\.\d{1,2})?$/', $_POST['starting_price']) || floatval($_POST['starting_price']) <= 0){
        $error[] = "Invalid price format.";
    }

    #VALIDAZIONE DEADLINE, CHE SIA MAGGIORE DELLA DATA E ORA ATTUALI
    $deadline = $_POST['deadline'];
    $current_time = date('Y-m-d H:i:s'); //restituisce una stringa che rappresenta la data e l'ora attuali in quel formato
    if (strtotime($current_time) > strtotime($deadline)) { //converte in timestampunix
            $error[] = "The deadline must be a future date.";
    }

    #CONTROLLO IMMAGINE
    $target_dir = "C:/xampp/htdocs/hw1/uploads/";
    $target_file = $target_dir . basename($_FILES["uploaded_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); //estensione del file caricato e in lettere minuscole
    // Check se l'immagine è reale o fake
    $check = getimagesize($_FILES["uploaded_image"]["tmp_name"]); //["tmp_name"]: Il nome del file temporaneo che è stato memorizzato sul server durante il caricamento.
    if($check == false) {
        $error[] = "Uploaded file is not an image.";
    }
    // Check dimensione
    if ($_FILES["uploaded_image"]["size"] > 500000) { //["size"]: restituisce in byte la dimenzione del file
        $error[] = "Uploaded image is too large.";
    }
    //Check formato
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $error[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";

    }

    #SE TUTTO E' ANDATO BENE CARICO L'ASTA SUL DB E REINDIRIZZO A running_auction.php
    if (count($error) == 0) {
        if (move_uploaded_file($_FILES["uploaded_image"]["tmp_name"], $target_file)) {
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
            $starting_price = mysqli_real_escape_string($conn, floatval($_POST['starting_price'])); 
            $target_file = mysqli_real_escape_string($conn, $target_file); 
            $userid = mysqli_real_escape_string($conn, $userid); 

            $query_insert = "INSERT INTO auctions (user_id, foto, titolo, durata, prezzo_iniziale) VALUES($userid, '$target_file', '$title', '$deadline', '$starting_price')";
            $res = mysqli_query($conn, $query_insert)  or die("Errore: ". mysqli_connect_error());
            if($res){
                header("Location: running_auction.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }else{
                $error[] = "Something went wrong.";
            }
        } else {
            $error[] = "Error during saving image.";    
        }
    }    
}
?>

<html>
  <head>
    <meta charset="utf-8">
      <link rel="icon" type="image/png" href="images/logo_mini.png">
      <title>New Auction | MoMA</title>
      <link rel="stylesheet" href="new_auction.css"/>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ysabeau+Infant:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <section id="background">
      <section id="main">
          <div id="new_auction">
            <img id="logo" src="images\logo.png"/>
            <h1>Create a new auction.</h1>
            <?php 
            if(isset($error)) {
                    foreach($error as $err) {
                        echo "<div class='error_php'>".$err."</div>";
                    }
            } 
            ?>
            <form name="form_new_auction" method="post" enctype="multipart/form-data">
              <input type="file" name="uploaded_image" placeholder="Select image to upload" class="input">
              <input type="input" name="title" placeholder="Title" class="input" <?php if(isset($_POST["title"])){echo "value=".$_POST["title"];} ?> >             
              <input type="input" name="deadline" placeholder="Deadline YYYY-MM-DD HH:MM:SS" class="input" <?php if(isset($_POST["deadline"])){echo "value=".$_POST["deadline"];} ?> >
              <input type="input" name="starting_price" placeholder="Starting price" class="input" <?php if(isset($_POST["starting_time"])){echo "value=".$_POST["starting_time"];} ?> >
              <input type="submit" value="Create new auction" class="button">
            </form>
            <a href="http://localhost/hw1/index.php">&#8592; Return to home page</a>
            <a href="http://localhost/hw1/personal_area.php">&#8592; Return to personal area</a>
          </div>
      </section>
    </section>
  </body>