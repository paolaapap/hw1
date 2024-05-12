<?php
    session_start();

    if(isset($_SESSION["email"])){
        header("Location: index_logged.php");
        exit;
    }

    
    if(isset($_POST["email"]) && isset($_POST["password"])){
    
        $conn = mysqli_connect("localhost", "root", "", "utenti") or die("Errore: " .mysqli_connect_error());

		    $email = mysqli_real_escape_string($conn,$_POST['email']);
		    $password = mysqli_real_escape_string($conn,$_POST['password']);


		    $query = "SELECT * FROM users WHERE email ='" . $email . "' AND password = '" .$password."'";

        $res = mysqli_query($conn, $query) or die("Errore: ". mysqli_connect_error());

       if(mysqli_num_rows($res) > 0){

            $_SESSION["email"] = $_POST["email"];
            header("Location: index_logged.php");
            exit;
        }
        else{
            $errore = true;
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
                if(isset($errore)){
                    echo "<h1 class='error'>";
                    echo "We couldn’t log you in with the email and password you provided. Please try again.";
                    echo "</h1>";
                }
              ?>
              <form name="form_login" method="post">

                <input type="email" name="email" placeholder="Email address" class="input">
                <div id="email_error" class="error hidden">Enter your email</div>

                <input type="password" name="password" placeholder="Password" class="input">
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