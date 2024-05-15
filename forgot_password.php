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
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: " .mysqli_connect_error());
    $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));

    $query = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($conn, $query) or die("Errore: ". mysqli_connect_error());

    if(mysqli_num_rows($res) > 0) 
    { 
        $token = rand(10000, 99999);

        $insert_query = "INSERT INTO reset_password VALUES ('$email', '$token')";
        $res2 = mysqli_query($conn, $insert_query) or die("Errore: ". mysqli_connect_error());

        if($res2) 
        { 
            $_SESSION['token'] = $token;

            //invio email
            $subject= "Reset password MoMA account";
            $from= "momanewyork00@gmail.com";
            $message = "This is your token to reset you password \n $token \n To reset your password click this link \n localhost/hw1/reset_password.php";
            //$message .= "<h1>RESET PASSWORD OF MOMA ACCOUNT</h1>";
            //$message .= "<span>This is your token to reset you password <br>$token<br> </span>";
            //$message .= "<span>To reset your password click the link below <br><a href=\"localhost/hw1/reset_password.php\">Link to reset password</a></span>";
            //$message .= "</body></html>";
            $headers =['from' => $from];

            $res3=mail($email, $subject, $message, $headers);

            if ($res3) 
            {
                header("Location: success_email.php");
                exit;
            } 
            else 
            {
                header("Location: failed_email.php");
                exit;
            }
        } 
        else 
        {
            $error = true;
        }
    } 
    else 
    {
        $user_notexist = true;
    }

    mysqli_free_result($res);
    mysqli_free_result($res2);
    mysqli_close($conn);
}

?>
<html>
  <head>
    <meta charset="utf-8">
      <title>FORGOT PASSWORD</title>
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
                if(isset($user_notexist)){
                    echo "<h1 class='error'>";
                    echo "There is no account associated with this email. Go to the new account section.";
                    echo "</h1>";
                }
                if(isset($error)){
                  echo "<h1 class='error'>";
                  echo "Something went wrong.";
                  echo "</h1>";
              }
              ?>
            <form name="form_forgot_password" method="post">

              <input type="email" name="email" placeholder="Email address" class="input">
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