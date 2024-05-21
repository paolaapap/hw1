<?php
    require_once 'auth.php';

    if (checkAuth()) {
          header('Location: index_logged.php');
          exit;
    }

      
    if(isset($_POST["email"]) && isset($_POST["password"])){
        
        $error = array();
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: " .mysqli_connect_error());
		    $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));

        //controllo intanto se esiste qualcuno che è registrato al sito con questa email
        $query="SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $query) or die("Errore: ". mysqli_connect_error());
        
        if(mysqli_num_rows($res) > 0){ //se esiste un utente registrato con questa mail, prendi la password
          
          $entry = mysqli_fetch_assoc($res);
          
          if (password_verify($_POST['password'], $entry['password'])) {
            if(isset($_POST['remember'])){
              $token = random_bytes(12);
              $hash = password_hash($token, PASSWORD_BCRYPT);
              $expires = time() + (7 * 24 * 60 * 60);
              $query = "INSERT INTO cookies (user_id, hash, expires) VALUES(".$entry['id'].", '".$hash."', ".$expires.")";
              $res = mysqli_query($conn, $query) or die("Errore: ". mysqli_connect_error());
              setcookie("user_id", $entry['id'], $expires);
              setcookie("cookie_id", mysqli_insert_id($conn), $expires);
              setcookie("token", $token, $expires);
              
            } else{
              $_SESSION["user_id"] = $entry['id'];
            }
            header("Location: index_logged.php");
            mysqli_free_result($res);
            mysqli_close($conn);
            exit;

          } else{
            $error[] = "Wrong password. Try again or go to forgot password section.";
          }  
        } else{
          $error[] = "You aren't registered with the email address you provided. Change email address or create a new account.";
        }
    }

?>

 <html>
  <head>
    <meta charset="utf-8">
      <link rel="icon" type="image/png" href="images/logo_mini.png">
      <title>Login | MoMA</title>
      <link rel="stylesheet" href="login.css"/>
      <script src="login.js" defer></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ysabeau+Infant:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
      <section id="background">
        <section id="main">
            <div id="login">
              <img id="logo" src="images\logo.png"/>
              <h1>Log in to your account with your email and password.</h1>
              <?php 
              if(isset($error)) {
                    foreach($error as $err) {
                        echo "<div class='error_php'>".$err."</div>";
                    }
              } 
              ?>
              <form name="form_login" method="post">
                <input type="email" name="email" placeholder="Email address" class="input" <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>>
                <input type="password" name="password" placeholder="Password" class="input" <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
                <img id="show_pss" src="images\show_pss.jpg" /> 
                <input type="submit" value="Log in" class="button">
                <label id="remember"><input type="checkbox" name="remember"> Remember Me</label>
              </form>
              <a href="http://localhost/hw1/forgot_password.php">Forgot your password?</a>
            </div>
            <div id="new_account">
              <div><a href="http://localhost/hw1/new_account.php">
                <h1>Create your new account login &#8594;</h1>
                <span>We recently updated our sign-in process. If you have never created an online account with MoMA, click here to create one now.</span>
              </a></div>
              <a href="http://localhost/hw1/index.php" id="return">&#8592; Return to home page</a>
            </div>
        </section>
      </section>
  </body>