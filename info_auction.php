<?php
    require_once 'auth.php';

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

    if(isset($_GET["id"])) {
        $auction_id = $_GET["id"];
        $query = "SELECT id FROM auctions ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        if($result = mysqli_fetch_assoc($res)){
            if($auction_id > $result['id']){ //cioe se provo a fare un'offerta ma nel frattempo scade l'asta e quindi viene rimossa dal db
                header("Location: running_auction.php"); 
                exit;  
            }
        }
    } else {
        header("Location: running_auction.php");
    }

    if(isset($_POST["offers"])){
        if (!$userid = checkAuth()) {
            header("Location: login.php");
            exit;

        } else {

            $error = array();

            #CONTROLLO CHE IL PREZZO INSERITO SIA MAGGIORE DELL'UTLIMA OFFERTA E DEL PREZZO BASE
            $auction_id = mysqli_real_escape_string($conn, $_GET["id"]);
            $query_check = "SELECT ultimo_prezzo, prezzo_iniziale FROM auctions WHERE id=$auction_id LIMIT 1";
            $res = mysqli_query($conn, $query_check) or die(mysqli_error($conn));
            if($entry = mysqli_fetch_assoc($res)){
                $offerta = mysqli_real_escape_string($conn, $_POST["offers"]);
                if($offerta < $entry["ultimo_prezzo"]){
                    $error[] = "Your offer must be greater than the latest price.";
                } else if ($offerta < $entry["prezzo_iniziale"]){
                    $error[] = "Your offer must be greater than the starting price.";
                }
            }

            #CONTROLLO CHE L'UTENTE NON FACCIA UN'OFFERTA AD UN'ASTA CHE HA CREATI LUI STESSO
            $query_check_user = "SELECT * FROM auctions WHERE id=$auction_id";
            $res = mysqli_query($conn, $query_check_user) or die(mysqli_error($conn));
            if($entry = mysqli_fetch_assoc($res)){
                if($entry['user_id'] == $userid){
                    $error[] = "You cannot bid on an auction you own";
                }
            }

            #SE NON CI SONO STATI ERRORI FACCIO L'OFFERTA
            if(count($error)==0){
                $user_id = mysqli_real_escape_string($conn, $userid);
                $query = "INSERT INTO offers (user_id, auction_id, prezzo) VALUES ($user_id, $auction_id, '$offerta')";
                $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

                $query = "SELECT user_id, titolo FROM auctions WHERE id=$auction_id";
                $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

                if($entry = mysqli_fetch_assoc($res)){
                    $user_not_id = $entry["user_id"];
                    $user_not_id = mysqli_real_escape_string($conn, $user_not_id);
                    $title = $entry["titolo"];
                    $content = "You have a new offer for your auction: " . $title . ". Amount: " .$offerta . "$";
                    $query = "INSERT INTO notifications (content, user_id) VALUES ('$content' , $user_not_id)";
                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                }

            }
        }

    }
?>
<script>
const auction_id = "<?php echo $auction_id; ?>";
</script>
<!DOCTYPE html>
 <html>
  <head>
    <meta charset="utf-8">
      <link rel="icon" type="image/png" href="images/logo_mini.png">
      <title>Auction details | MoMA</title>
      <link rel="stylesheet" href="info_auction.css"/>
      <script src="info_auction.js" defer></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Ramabhadra&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Ysabeau+Infant:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <header id="fix_header">
        <div id="header_nav_upper">
            <img class="logo" src="images\logo.png"/>
            <?php
            require_once 'auth.php';
            if ($userid = checkAuth()) {
                echo '<a class="a1" href="http://localhost/hw1/personal_area.php">Personal area</a>';
            } else {
                echo '<a class="a1" href="http://localhost/hw1/index.php">Home</a>';
            }
            ?> 
        </div>
        <div id="header_nav_lower">
            <span>Visit</span> 
            <span class="current_page">Auctions</span>  
            <span>Art and artist</span>  
            <span>Store</span>   
            <a href="http://localhost/hw1/running_auction.php">Running auctions</a>
            <div class="header_nav_lower_right"></div>
        </div>
    </header>
    <section id="dinamic_header" class='hidden'>
            <div class="left">
                <span>Visit</span> 
                <span class="current_page">Auctions</span>  
                <span>Art and artist</span>  
                <span>Store</span>    
                <a href="http://localhost/hw1/running_auction.php">Running auctions</a> 
            </div>
            <div class="right">
                <?php
                if ($userid = checkAuth()) {
                    echo '<a class="a1" href="http://localhost/hw1/personal_area.php">Personal area</a>';
                } else {
                    echo '<a class="a1" href="http://localhost/hw1/index.php">Home</a>';
                }
                ?>

            </div>
    </section>
    <section id="show_auction">
        <div id="image"></div>
        <div id="details">
        <?php
        if(isset($error)) {
            foreach($error as $err) {
                echo "<h1 style='color:red'>".$err."</h1>";
            }
          } 
        ?>
        </div>
    </section>