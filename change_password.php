<?php
require_once 'auth.php';

if(isset($_POST['email']) && isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['new_password_confirm'])){

        $error = array();
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: " .mysqli_connect_error());    
        
        #CONTROLLO LA PASSWORD
        if(!(preg_match('/.{8,}/', $_POST['new_password']) && preg_match('/[A-Z]/', $_POST['new_password']) && preg_match('/[a-z]/', $_POST['new_password']) &&
            preg_match('/[0-9]/', $_POST['new_password']) && preg_match('/[!@#$%^&*(),.?]/', $_POST['new_password']))){
            $error[] = "Password not valid.";
        }

        #CONTROLLO MATCH TRA LE PASSWORD NUOVE
        if (strcmp($_POST['new_password'], $_POST['new_password_confirm']) != 0) {
            $error[] = "Passowrds don't match.";
        }

        #CONTROLLO CHE LA VECCHIA PASSWORD NON SIA UGUALE ALLA NUOVA
        if (strcmp($_POST['new_password'], $_POST['old_password']) == 0) {
          $error[] = "Your new password must be different from your old.";
        }

        #CONTROLLO CHE L'UTENTE ESISTA NEL DB, SE ESISTE VERIFICO OLD_PASSOWRD
        $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
        $query = "SELECT * FROM users WHERE email='$email'";
        $res = mysqli_query($conn, $query)  or die("Errore: ". mysqli_connect_error());
        if(mysqli_num_rows($res) == 0){
            $error[] = "There is no account associated with this email.";
        } else {
            $entry = mysqli_fetch_assoc($res);
            if (!password_verify($_POST['old_password'], $entry['password'])) {
                $error[] = "The password is wrong. Try again.";    
            }
        }


        #SE NON CI SONO STATI ERRORI, FACCIO L'UPDATE
        if(count($error) == 0){
            
            $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
            $new_password = password_hash($new_password, PASSWORD_BCRYPT);
            $query_update = "UPDATE users SET password ='" . $new_password . "' WHERE email ='" . $email . "'";
            $res = mysqli_query($conn, $query_update)  or die("Errore: ". mysqli_connect_error());    
            if($res){
                $userid = $entry["id"];
                $_SESSION["user_id"] = $userid;
                header("Location: index.php");
                mysqli_free_result($res); 
                exit;  
            } else {
                $error[] = "Unexpected error while changing password. Please try again.";
            }
        }
        
        mysqli_close($conn);
}
?>
 
 <html>
  <head>
    <meta charset="utf-8">
      <link rel="icon" type="image/png" href="images/logo_mini.png">
      <title>Change password | MoMA</title>
      <link rel="stylesheet" href="change_password.css"/>
      <script src="change_password.js" defer></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ysabeau+Infant:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <section id="background">
      <section id="main">
          <div id="change_password">
            <img id="logo" src="images\logo.png"/>
            <h1>Change your password.</h1>
            <?php 
            if(isset($error)) {
                    foreach($error as $err) {
                        echo "<div class='error_php'>".$err."</div>";
                    }
            } 
            ?>
            <form name="form_change_password" method="post">
              <input type="email" name="email" placeholder="Email address" class="input" <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?> >
              <input type="password" name="old_password" placeholder="Old password" class="input" <?php if(isset($_POST["old_password"])){echo "value=".$_POST["old_password"];} ?>>
              <input type="password" name="new_password" placeholder="New password" class="input" <?php if(isset($_POST["new_password"])){echo "value=".$_POST["new_password"];} ?>>
              <input type="password" name="new_password_confirm" placeholder="Repeat new password" class="input" <?php if(isset($_POST["new_password_confirm"])){echo "value=".$_POST["new_password_confirm"];} ?>>
              <img id="show_pss" src="images\show_pss.jpg" /> 
              <input type="submit" value="Change password" class="button">
            </form>
            <a href="http://localhost/hw1/personal_area.php">&#8592; Return to your personal area</a>
            <a href="http://localhost/hw1/index.php">&#8592; Return to home page</a>
          </div>
      </section>
    </section>
  </body>