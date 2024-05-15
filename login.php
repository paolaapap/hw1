<?php
    require_once 'auth.php';

    if (checkAuth()) {
          header('Location: index_logged.php');
          exit;
    }

      
    if(isset($_POST["email"]) && isset($_POST["password"])){
    
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: " .mysqli_connect_error());

		    $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));

        //controllo intanto se esiste qualcuno che è registrato al sito con questa email
        $query="SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $query) or die("Errore: ". mysqli_connect_error());
        
        if(mysqli_num_rows($res) > 0){ //se esiste un utente registrato con questa mail, prendi la password
          $entry = mysqli_fetch_assoc($res);
          
          if (password_verify($_POST['password'], $entry['password'])) {

                $_SESSION["user_id"] = $entry['id'];
                header("Location: index_logged.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
          } else{
            $wrong_password = true;
          }  
        } else{
          $user_notexists = true;
        }
    }

?>

 <html>
  <head>
    <meta charset="utf-8">
      <title>LOG IN</title>
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
                if(isset($wrong_password)){
                    echo "<h1 class='error_php'>";
                    echo "Wrong password. Try again or go to forgot password section.";
                    echo "</h1>";
                }
                if(isset($user_notexists)){
                  echo "<h1 class='error_php'>";
                  echo "You aren't registered with the email address you provided. Change email address or create a new account.";
                  echo "</h1>";
              }
              ?>
              <form name="form_login" method="post">

                <input type="email" name="email" placeholder="Email address" class="input" <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>>
                <div id="email_error" class="error hidden">Enter your email</div>

                <input type="password" name="password" placeholder="Password" class="input" <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
                <div id="password_error" class="error hidden">Enter your password</div>

                <img id="show_pss" src="images\show_pss.jpg" /> 
                
                <input type="submit" value="Log in" class="button">
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