<?php
session_start();

if(isset($_SESSION["email"])){
    header("Location: index_logged.php");
    exit;
}

if(isset($_POST['last_name']) && isset($_POST['first_name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_confirm'])){

    $conn = mysqli_connect("localhost", "root", "", "utenti") or die("Errore: ". mysqli_connect_error());
    $cognome = mysqli_real_escape_string($conn, $_POST['last_name']);
    $nome = mysqli_real_escape_string($conn, $_POST['first_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($conn, $query)  or die("Errore: ". mysqli_connect_error());
    
    if(mysqli_num_rows($res) > 0){
        $utente_registrato = true;
    }
    else {
        if($password==$password_confirm){  
          $query_insert = "INSERT INTO users VALUES('$email','$password','$nome','$cognome')";
          $res2 = mysqli_query($conn, $query_insert)  or die("Errore: ". mysqli_connect_error());
          if($res2){
              $_SESSION["email"]=$email;
              header("Location: index_logged.php");
              exit;
          }else{
              $error = true;
          }
        }
    }        
    
}
?>

 <html>
  <head>
    <meta charset="utf-8">
      <title>NEW ACCOUNT</title>
      <link rel="stylesheet" href="new_account.css"/>
      <script src="new_account.js" defer></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ysabeau+Infant:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <section id="background">
      <section id="main">
          <div id="new_account">
            <img id="logo" src="images\logo.png"/>
            <h1>Create your new account log in.</h1>
            <?php
                if(isset($error)){
                    echo "<h1 class='error_php'>";
                    echo "Unexpected error while registering account. Please try again.";
                    echo "</h1>";
                } 
                if(isset($utente_registrato)){
                    echo "<h1 class='error_php'>";
                    echo "You have already registed this email. Please go to Login section or change email.";
                    echo "</h1>";
                }
              ?>
            <form name="form_new_account" method="post">

              <input type="input" name="last_name" placeholder="Last name" class="input">
              <div id="last_name_error" class="error hidden">Enter your last name</div>
              <div id="last_name_notvalid" class="error hidden">Insert a valid last name</div>

              <input type="input" name="first_name" placeholder="First name" class="input">
              <div id="fisrt_name_error" class="error hidden">Enter your first name</div>
              <div id="first_name_notvalid" class="error hidden">Insert a valid first name</div>

              <input type="email" name="email" placeholder="Email address" class="input">
              <div id="email_error" class="error hidden">Enter your email</div>

              <input type="password" name="password" placeholder="Create password" class="input">
              <div id="password_error" class="error hidden">Enter your password</div>
              <div id="password_requirements" class="error hidden">Password must be at least 8 characters, 1 uppercase, 1 lowercase, 1 number and 1 special character [!@#$%^&*(),.?]</div>
              <div id="password_accepted" class="right hidden">This password is valid</div>

              <input type="password" name="password_confirm" placeholder="Confirm password" class="input">
              <div id="password_confirm_error" class="error hidden">Repeat password</div>
              <div id="password_match" class="right hidden">Passwords match</div>
              <div id="password_dont_match" class="error hidden">Passwords don't match</div>

              <img id="show_pss" src="images\show_pss.jpg" /> 
              
              <input type="submit" value="Create account" class="button">
            </form>
            <a href="http://localhost/hw1/index.php">&#8592; Return to home page</a>
            <a href="http://localhost/hw1/login.php">&#8592; Return to log in</a>
          </div>
      </section>
    </section>
  </body>