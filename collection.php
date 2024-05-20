<!DOCTYPE html>
 <html>
  <head>
    <meta charset="utf-8">
      <link rel="icon" type="image/png" href="images/logo_mini.png">
      <title>The collection | MoMA</title>
      <link rel="stylesheet" href="collection.css"/>
      <script src="collection.js" defer></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Ramabhadra&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Ysabeau+Infant:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <header id="fix_header">
        <div id="header_nav_upper">
            <img class="logo" src="images\logo.png"/>
            <?php
            require_once 'auth.php';
            if ($userid = checkAuth()) {
                echo '<a href="http://localhost/hw1/personal_area.php">Personal Area</a>';
            } else {
                echo '<a href="http://localhost/hw1/login.php">Login</a>';
            }
            ?> 
        </div>
        <div id="header_nav_lower">
            <span>Visit</span> 
            <span>What's on</span>  
            <span class="current_page">Art and artist</span>  
            <span>Store</span>   
            <div class="header_nav_lower_right"></div>
        </div>
    </header>
    <section id="dinamic_header" class='hidden'>
            <div class="left">
                <span>Visit</span> 
                <span>What's on</span>  
                <span class="current_page">Art and artist</span>  
                <span>Store</span>     
            </div>
            <div class="right">
                <?php
                if ($userid = checkAuth()) {
                    echo '<a class="a1" href="http://localhost/hw1/personal_area.php">Personal Area</a>';
                } else {
                    echo '<a class="a1" href="http://localhost/hw1/login.php">Login</a>';
                }
                ?>
            </div>
    </section>
    <h1 id="page_title">The Collection</h1>
    <section id="filter">
        <input list="filters" id="filter_input" placeholder="Filter by categories" spellcheck="false">
        <datalist id="filters"></datalist>
    </section>
    <section id="artworks_section"></section>