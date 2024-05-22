<?php
require_once 'auth.php';

if (checkAuth()) {
    header("Location: index_logged.php");
    exit;
}   

if(isset($_POST['last_name']) && isset($_POST['first_name']) && isset($_POST['email']) && 
  isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['gender'])){

    $error = array();
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: ". mysqli_connect_error());
    
    #VALIDAZIONE COGNOME
    if(!preg_match('/^[a-zA-ZàèìòùÀÈÌÒÙçÇ ]+$/', $_POST['last_name'])){
      $error[] = "Last name not valid.";
    }

    #VALIDAZIONE NOME
    if(!preg_match('/^[a-zA-ZàèìòùÀÈÌÒÙçÇ ]+$/', $_POST['first_name'])){
      $error[] = "First name not valid.";
    }

    #VALIDAZIONE EMAIL
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $error[] = "Email not valid.";
    } else {
      $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));  
      $query = "SELECT * FROM users WHERE email = '$email'";
      $res = mysqli_query($conn, $query)  or die("Errore: ". mysqli_connect_error());
    
      if(mysqli_num_rows($res) > 0){
        $error[] = "Email already used.";
      }
    }

    #VALIDAZIONE PASSOWRD
    if(!(preg_match('/.{8,}/', $_POST['password']) && preg_match('/[A-Z]/', $_POST['password']) && preg_match('/[a-z]/', $_POST['password']) &&
        preg_match('/[0-9]/', $_POST['password']) && preg_match('/[!@#$%^&*(),.?]/', $_POST['password']))){
      $error[] = "Password not valid.";
    }

    #MATCH PASSWORD
    if (strcmp($_POST['password'], $_POST['password_confirm']) != 0) {
      $error[] = "Passowrds don't match";
    }

    #REGISTRAZIONE DEL DATABASE
    if (count($error) == 0) {
      $cognome = mysqli_real_escape_string($conn, $_POST['last_name']);
      $nome = mysqli_real_escape_string($conn, $_POST['first_name']);
      $gender = mysqli_real_escape_string($conn, $_POST['gender']);
      $password = mysqli_real_escape_string($conn, $_POST['password']);
      $password = password_hash($password, PASSWORD_BCRYPT);

      $query_insert = "INSERT INTO users(email, password, nome, cognome, genere) VALUES('$email','$password','$nome','$cognome', '$gender')";
      $res = mysqli_query($conn, $query_insert)  or die("Errore: ". mysqli_connect_error());
      if($res){
              $_SESSION["user_id"]=mysqli_insert_id($conn);
              header("Location: index_logged.php");
              mysqli_free_result($res);
              mysqli_close($conn);
              exit;
          }else{
              $error[] = "Something went wrong.";
          }
    }    
} 
?>

 <html>
  <head>
    <meta charset="utf-8">
      <link rel="icon" type="image/png" href="images/logo_mini.png">
      <title>New Account | MoMA</title>
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
            <h1>Create your new account.</h1>
            <?php 
            if(isset($error)) {
                    foreach($error as $err) {
                        echo "<div class='error_php'>".$err."</div>";
                    }
            } 
            ?>
            <form name="form_new_account" method="post">
              <input type="input" name="last_name" placeholder="Last name" class="input" <?php if(isset($_POST["last_name"])){echo "value=".$_POST["last_name"];} ?> >
              <input type="input" name="first_name" placeholder="First name" class="input" <?php if(isset($_POST["first_name"])){echo "value=".$_POST["first_name"];} ?> > 
              <select id="gender" name="gender" placeholder="Gender" class="input" <?php if(isset($_POST["gender"])){echo "value=".$_POST["gender"];} ?>>
                <option value="Other">Non-Specific</option>  
                <option value="Female">Female</option>
                <option value="Male">Male</option>
              </select>            
              <input type="email" name="email" placeholder="Email address" class="input" <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?> >
              <input type="password" name="password" placeholder="Create password" class="input" <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?> >
              <input type="password" name="password_confirm" placeholder="Confirm password" class="input" <?php if(isset($_POST["password_confirm"])){echo "value=".$_POST["password_confirm"];} ?> >
              <img id="show_pss" src="images\show_pss.jpg" /> 
              <input type="submit" value="Create account" class="button">
            </form>
            <a href="http://localhost/hw1/index.php">&#8592; Return to home page</a>
            <a href="http://localhost/hw1/login.php">&#8592; Return to log in</a>
          </div>
      </section>
    </section>
  </body>