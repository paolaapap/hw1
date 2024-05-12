<?php
session_start();
 
if(isset($_SESSION['token']))
{
    //se il token inserito è uguale a session token AND esiste quel token in reset_passowrd allora
    //-> se in reset_password quel token è associato a quella email -> fai la query che cambia la password, fai la sessione con session email e chiudi la sessione con session token e manda in index_logged

    //se il token è sbagliato ERRORE con stringa rossa in alto "This token is not valid. Try again." (cioè se il token non esiste nel db)
    //se la query non restituisce risultati ERRORE con stringa rossa in alto "There is not a token associated with this email. Return to forgot password section" (cioe se nel db non esiste la riga email & token)
    if(isset($_POST['token']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_confirm'])){
        $conn = mysqli_connect("localhost", "root", "", "utenti") or die("Errore: " .mysqli_connect_error());

        
        $token = mysqli_real_escape_string($conn, $_POST['token']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);

        $query_token = "SELECT * FROM reset_password WHERE token='$token'";
        $res = mysqli_query($conn, $query_token)  or die("Errore: ". mysqli_connect_error());

        if($token == $_SESSION["token"] && mysqli_num_rows($res) > 0)
        { //verifico se la sessione è associata al token giusto e se il token effettivamente esiste nel db
          
            $query_control = "SELECT * FROM reset_password WHERE email ='" . $email . "' AND token = '" .$token."'";
            $res2 = mysqli_query($conn, $query_control)  or die("Errore: ". mysqli_connect_error());

            if(mysqli_num_rows($res2) > 0 and $password==$password_confirm)
            { //allora permette la modifica della password, fa la sessione con session email ed elimina la sessione del token e manda ad index_logged
                  $query_update = "UPDATE users SET password ='" . $password . "' WHERE email ='" . $email . "'";
                  $res3 = mysqli_query($conn, $query_update)  or die("Errore: ". mysqli_connect_error());
                  
                  if($res3)
                  {
                    unset($_SESSION['token']);
                    $_SESSION["email"] = $email;
                    header("Location: index_logged.php"); 
                    exit;  
                  } 
                  else 
                  {
                    $error = true;
                  }
            }
            else
            {
              $not_association = true;
            }

        } 
        else
        {
          $token_notvalid=true;
        }
    }
} 
else
{
    header("Location: forgot_password.php");
    exit;
}
?>

 <html>
  <head>
    <meta charset="utf-8">
      <title>RESET PASSWORD</title>
      <link rel="stylesheet" href="reset_password.css"/>
      <script src="reset_password.js" defer></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ysabeau+Infant:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <section id="background">
      <section id="main">
          <div id="reset_password">
            <img id="logo" src="images\logo.png"/>
            <h1>Reset your password.</h1>
            <?php
                if(isset($token_notvalid)){
                    echo "<h1 class='error_php'>";
                    echo "This token is not valid. Try again. ";
                    echo "</h1>";
                } 

                if(isset($not_association)){
                  echo "<h1 class='error_php'>";
                  echo "There is not a token associated with this email. Return to forgot password section.";
                  echo "</h1>";
                } 
                if(isset($error)){
                  echo "<h1 class='error_php'>";
                  echo "Unexpected error while changing password. Please try again.";
                  echo "</h1>";
              } 
            ?>
            <form name="form_reset_password" method="post">

              <input type="email" name="email" placeholder="Email address" class="input">
              <div id="email_error" class="error hidden">Enter your email</div>

              <input type="input" name="token" placeholder="Token" class="input">
              <div id="token_error" class="error hidden">Enter token</div>
              <div id="token_notvalid" class="error hidden">Token is not valid</div>

              <input type="password" name="password" placeholder="Create a new password" class="input">
              <div id="password_error" class="error hidden">Enter your new password</div>
              <div id="password_requirements" class="error hidden">Password must be at least 8 characters, 1 uppercase, 1 lowercase, 1 number and 1 special character [!@#$%^&*(),.?]</div>
              <div id="password_accepted" class="right hidden">This password is valid</div>

              <input type="password" name="password_confirm" placeholder="Confirm password" class="input">
              <div id="password_confirm_error" class="error hidden">Repeat password</div>
              <div id="password_match" class="right hidden">Passwords match</div>
              <div id="password_dont_match" class="error hidden">Passwords don't match</div>

              <img id="show_pss" src="images\show_pss.jpg" /> 
              
              <input type="submit" value="Reset password" class="button">
            </form>
            <a href="http://localhost/hw1/index.php">&#8592; Return to home page</a>
            <a href="http://localhost/hw1/login.php">&#8592; Return to log in</a>
            <a href="http://localhost/hw1/forgot_password.php">&#8592; Return to forgot password</a>
          </div>
      </section>
    </section>
  </body>