<?php 
    require_once 'auth.php';
    if (!$userid = checkAuth()) {
        header("Location: login.php");
        exit;
    } else{

        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: " .mysqli_connect_error());
        $userid = mysqli_real_escape_string($conn, $userid);
        $query = "SELECT * FROM users WHERE id = $userid";

        $res = mysqli_query($conn, $query) or die("Errore: ". mysqli_connect_error());

       if(mysqli_num_rows($res) > 0){
            $userinfo = mysqli_fetch_assoc($res);
            $nome = $userinfo["nome"];
            $cognome = $userinfo["cognome"];
            $genere = $userinfo["genere"];
        }
        else{
            $name_notfound = true;
        }

        mysqli_free_result($res);
        mysqli_close($conn);
    }
?>
<!DOCTYPE html>
 <html>
  <head>
    <meta charset="utf-8">
      <link rel="icon" type="image/png" href="images/logo_mini.png">
      <title>Personal Area | MoMA</title>
      <link rel="stylesheet" href="personal_area.css"/>
      <script src="personal_area.js" defer></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ysabeau+Infant:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
  <header id="fix_header">
        <div id="header_nav_upper">
            <img class="logo" src="images\logo.png"/>
            <a href="http://localhost/hw1/logout.php">Logout</a>
        </div>
        <div id="header_nav_lower">
            <a href="http://localhost/hw1/index_logged.php">Home</a> 
            <a href="http://localhost/hw1/change_password.php">Change Password</a>  
            <a href="http://localhost/hw1/new_auction.php">New auction</a>  
            <a href="http://localhost/hw1/running_auction.php">Running auction</a>  
            <a href="http://localhost/hw1/collection.php">Collection</a>
            <div class="header_nav_lower_right"></div>
        </div>
    </header>
    <section id="dinamic_header" class='hidden'>
            <div class="left">
            <a href="http://localhost/hw1/index_logged.php">Home</a> 
            <a href="http://localhost/hw1/change_password.php">Change Password</a>  
            <a href="http://localhost/hw1/new_auction.php">New auction</a>   
            <a href="http://localhost/hw1/running_auction.php">Running auction</a>  
            <a href="http://localhost/hw1/collection.php">Collection</a>
            </div>
            <div class="right">
                <?php
                if(isset($name_notfound)){
                        echo "<h1>Hello, $userid</h1>";
                    } else {
                        echo "<h1>Hello, $nome</h1>";
                    }
                

                if($genere == "Female"){
                    echo "<img src='images\women.jpg'/>";
                } else if ($genere == "Male"){
                    echo "<img src='images\man.jpg'/>";    
                } else {
                    echo "<img src='images\personal_area.jpeg'/>";
                }
                ?>
                <a href="http://localhost/hw1/logout.php">Logout</a>
            </div>
    </section>
    <section id="user">
        <?php 
            if($genere == "Female"){
                echo "<img src='images\women.jpg'/>";
            } else if ($genere == "Male"){
                echo "<img src='images\man.jpg'/>";    
            } else {
                echo "<img src='images\personal_area.jpeg'/>";
            }
            if(isset($name_notfound)){
                echo "<h1>Hello, $userid</h1>";
                } else {
                    echo "<h1>$cognome $nome</h1>";
                }
        ?>
    </section>
    <section id="collection">
        <h1>Favourites for my MoMA tour</h1>
        <div id="collection_figlio"></div>
    </section>
    <section id="auction_ongoing">
        <h1>My auction ongoing</h1>
        <div id="ongoing_figlio"></div>
    </section>
    <section id="auction">
        <h1>My auctions</h1>
        <div id="auction_figlio"></div>
    </section>
  </body>