<?php
//prendo l'email inserita dall'utente e verifico se l'utente è registrato (se esiste nella tabella users)
//->se non esiste "non sei registrato con questa email, vai alla sezione new_account"
//-> se esiste genero un token random numerico di 5 numeri 
//salvo in reset_password una riga email e token
//creo una sessione con quel token
//invio l'email 
// -> se l'invio va a buon fine mi reindirizzo ad una pagina identica ma con scritto che l'email è andata a buon fine e di NON chiudere il browser
// -> se l'invio NON va a buon fine mi reindirizzo ad una pagina identica ma con scritto che l'email NON è andata a buon fine

require_once 'auth.php';

if(isset($_POST['email'])) 
{   
    $error = array();
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: " .mysqli_connect_error());
    
    #CONTROLLO CHE L'UTENTE SIA REGISTRATO
    $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
    $query = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($conn, $query) or die("Errore: ". mysqli_connect_error());
    if(mysqli_num_rows($res) == 0){
        $error[] = "There is no account associated with this email. Go to the new account section.";
    } 

    #SE NON CI SONO ERRORI FACCIO L'INSERT NEL DB
    if(count($error) == 0){
        $token = rand(10000, 99999);
        $token_hash = password_hash($token, PASSWORD_BCRYPT);
        $insert_query = "INSERT INTO tokens(email, token) VALUES ('$email', '$token_hash')";
        $res = mysqli_query($conn, $insert_query) or die("Errore: ". mysqli_connect_error());   
        if(!($res)){
            $error[] = "Something went wrong.";
        }      
    }

    #SE ANCHE L'INSERT NEL DB E' ANDATO BENE MANDO LA MAIL E FACCIO LA SESSIONE
    if(count($error) == 0) { 
        
        //invio email
        $subject= "Reset password MoMA account";
        $from= "momanewyork00@gmail.com";
        $message = "This is your token to reset you password \n $token \n To reset your password click this link \n localhost/hw1/reset_password.php";
        $headers =['from' => $from];
        $res_mail=mail($email, $subject, $message, $headers);
        
        if ($res_mail) {
            header("Location: success_email.php");
            $_SESSION['token'] = $token;
            mysqli_free_result($res);
            mysqli_close($conn);
            exit;
        } else {
            header("Location: failed_email.php");
            mysqli_free_result($res);
            mysqli_close($conn);
            exit;
        }
    }

}
?>
<html>
  <head>
    <meta charset="utf-8">
      <link rel="icon" type="image/png" href="images/logo_mini.png">
      <title>Forgot password | MoMA</title>
      <link rel="stylesheet" href="forgot_password.css"/>
      <script src="forgot_password.js" defer></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ysabeau+Infant:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <section id="background">
      <section id="main">
          <div id="forgot_password">
            <img id="logo" src="images\logo.png"/>
            <h1>Enter the email associated with the account whose password you forgot.</h1>
            <span>You will receive an email to the address provided below with instructions to change your password.</span>
            <?php 
            if(isset($error)) {
                    foreach($error as $err) {
                        echo "<div class='error_php'>".$err."</div>";
                    }
            } 
            ?>
            <form name="form_forgot_password" method="post">

              <input type="email" name="email" placeholder="Email address" class="input" <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>>
              <div id="email_error" class="error hidden">Enter your email</div>

              <input type="submit" value="Send email" class="button">
            </form>
            <a href="http://localhost/hw1/login.php">&#8592; Return to login</a>
            <a href="http://localhost/hw1/new_account.php">&#8592; Go to new account section</a>
            <a href="http://localhost/hw1/index.php">&#8592; Return to home page</a>
          </div>
      </section>
    </section>
  </body>