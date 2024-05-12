<?php
    session_start();

    if(isset($_POST["email"]) && isset($_POST["old_password"]) && isset($_POST["new_password"]) && isset($_POST["new_password_confirm"]))
    {
        $conn = mysqli_connect("localhost", "root", "", "utenti") or die("Errore: ". mysqli_connect_error());
		    $email = mysqli_real_escape_string($conn,$_POST['email']);
		    $old_password = mysqli_real_escape_string($conn,$_POST['old_password']);
        $new_password = mysqli_real_escape_string($conn,$_POST['new_password']);
        $new_password_confirm = mysqli_real_escape_string($conn,$_POST['new_password_confirm']);

		$query = "SELECT * FROM users WHERE email ='" . $email . "'";
        $res = mysqli_query($conn, $query)  or die("Errore: ". mysqli_connect_error());
        if(mysqli_num_rows($res) > 0){

            $query_complete = "SELECT * FROM users WHERE email ='" . $email . "' AND password = '" .$old_password."'";
            $res2 = mysqli_query($conn, $query_complete)  or die("Errore: ". mysqli_connect_error());

            if(mysqli_num_rows($res2) > 0 and $new_password==$new_password_confirm){
                $query_update = "UPDATE users SET password ='" . $new_password . "' WHERE email ='" . $email . "'";
                $res = mysqli_query($conn, $query_update)  or die("Errore: ". mysqli_connect_error());
                if($res){
                  $_SESSION["email"] = $email;
                  header("Location: personal_area.php"); 
                  exit;   
                }
                else{
                  $error=true;
                }
            }else{
                $wrong_password = true;
            }
        }
        else{
            $account_doesnt_exist = true;
        }
    }
?>
 
 <html>
  <head>
    <meta charset="utf-8">
      <title>CHANGE PASSWORD</title>
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
                if(isset($wrong_password)){
                    echo "<h1 class='error_php'>";
                    echo "The password is wrong. Try again.";
                    echo "</h1>";
                } 
                if(isset($account_doesnt_exist)){
                    echo "<h1 class='error_php'>";
                    echo "There is no account associated with this email.";
                    echo "</h1>";
                }
                if(isset($error)){
                  echo "<h1 class='error_php'>";
                  echo "Unexpected error while changing password. Please try again.";
                  echo "</h1>";
              } 
              ?>
            <form name="form_change_password" method="post">

              <input type="email" name="email" placeholder="Email address" class="input">
              <div id="email_error" class="error hidden">Enter your email</div>

              <input type="password" name="old_password" placeholder="Old password" class="input">
              <div id="old_password_error" class="error hidden">Enter your old password</div>

              <input type="password" name="new_password" placeholder="New password" class="input">
              <div id="new_password_error" class="error hidden">Enter a new password</div>
              <div id="new_password_requirements" class="error hidden">Password must be at least 8 characters, 1 uppercase, 1 lowercase, 1 number and 1 special character [!@#$%^&*(),.?]</div>
              <div id="new_password_accepted" class="right hidden">This password is valid</div>
              <div id="same_passwords" class="error hidden">Your new password must be different from your old</div>

              <input type="password" name="new_password_confirm" placeholder="Repeat new password" class="input">
              <div id="new_password_confirm_error" class="error hidden">Repeat password</div>
              <div id="password_match" class="right hidden">Passwords match</div>
              <div id="password_dont_match" class="error hidden">Passwords don't match</div>

              <img id="show_pss" src="images\show_pss.jpg" /> 

              <input type="submit" value="Change password" class="button">
            </form>
            <a href="http://localhost/hw1/personal_area.php">&#8592; Return to your personal area</a>
            <a href="http://localhost/hw1/index_logged.php">&#8592; Return to home page</a>
          </div>
      </section>
    </section>
  </body>