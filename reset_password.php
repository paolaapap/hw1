<?php
require_once 'auth.php';
if(isset($_SESSION['token'])){
    if(isset($_POST['token']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_confirm'])){

        $error = array();
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: " .mysqli_connect_error());    
        
        #CONTROLLO LA PASSWORD
        if(!(preg_match('/.{8,}/', $_POST['password']) && preg_match('/[A-Z]/', $_POST['password']) && preg_match('/[a-z]/', $_POST['password']) &&
            preg_match('/[0-9]/', $_POST['password']) && preg_match('/[!@#$%^&*(),.?]/', $_POST['password']))){
            $error[] = "Password not valid.";
        }

        #CONTROLLO MATCH TRA LE PASSWORD
        if (strcmp($_POST['password'], $_POST['password_confirm']) != 0) {
            $error[] = "Passowrds don't match.";
        }

        #CONTROLLO ASSOCIAZIONE TOKEN-SESSIONE
        if($_POST['token'] != $_SESSION['token']){
            $error[] = "Tokens don't match.";
        }

        #CONTROLLO CHE IL TOKEN ESISTA NEL DB
        $token = mysqli_real_escape_string($conn, $_POST['token']);
        $query_token = "SELECT * FROM reset_password WHERE token='$token'";
        $res = mysqli_query($conn, $query_token)  or die("Errore: ". mysqli_connect_error());
        if(mysqli_num_rows($res) == 0){
            $error[] = "Token doesn't exists.";
        }

        #CONTROLLO CHE ESISTA LA RIGA EMAIL-TOKEN
        $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
        $query_check = "SELECT * FROM reset_password WHERE email ='" . $email . "' AND token = '" .$token."'"; 
        $res = mysqli_query($conn, $query_check)  or die("Errore: ". mysqli_connect_error());
        if(mysqli_num_rows($res) == 0){
            $error[] = "Wrong token.";
        }

        #SE NON CI SONO STATI ERRORI, FACCIO L'UPDATE
        if(count($error) == 0){
            
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $password = password_hash($password, PASSWORD_BCRYPT);

            $query_update = "UPDATE users SET password ='" . $password . "' WHERE email ='" . $email . "'";
            $res = mysqli_query($conn, $query_update)  or die("Errore: ". mysqli_connect_error());

            //se l'update è andato bene cambio la sessione
            if($res){ 
                $query_id ="SELECT * FROM users WHERE email = '" . $email . "'";
                $res = mysqli_query($conn, $query_id) or die("Errore: ". mysqli_connect_error());
                
                if(mysqli_num_rows($res) > 0){
                    $userinfo = mysqli_fetch_assoc($res);
                    $userid = $userinfo["id"];
                    unset($_SESSION['token']);
                    $_SESSION["user_id"] = $userid;
                    header("Location: index_logged.php"); 
                    exit;  
                }
                else{
                    $error[] = "Problem on query_id.";
                }
            } else {
                $error[]= "Problem during update.";
            }
        }

        mysqli_close($conn);
    }

} else {
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
            if(isset($error)) {
                    foreach($error as $err) {
                        echo "<div class='error_php'>".$err."</div>";
                    }
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