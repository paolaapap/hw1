<?php
    require_once 'auth.php';

    if (!$userid = checkAuth()) {
        header("Location: login.php");
        exit;
    } else{

        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']) or die("Errore: " .mysqli_connect_error());
        $userid = mysqli_real_escape_string($conn, $userid);
        $query = "SELECT * FROM users WHERE id = $userid";

        $res = mysqli_query($conn, $query) or die("Errore: ". mysqli_connect_error());

       if(mysqli_num_rows($res) > 0){
            $userinfo = mysqli_fetch_assoc($res);
            $nome = $userinfo["nome"];
        }
        else{
            $name_notfound = true;
        }

        mysqli_free_result($res);
        mysqli_close($conn);
    }
?>

<!DOCTYPE html>
 <html>
  <head>
    <meta charset="utf-8">
      <title>INDEX LOGGED</title>
      <link rel="stylesheet" href="index_ logged.css"/>
      <script src="index_logged.js" defer></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lalezar&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Ramabhadra&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Ysabeau+Infant:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <header>
        <div class="header_nav_upper">
            <img class="logo" src="images\logo.png"/>
            <span class='orange hotel'>Nearby Hotel</span>
            <a class="a1" href="http://localhost/hw1/logout.php">Logout</a>
            <a class="a2" href="https://visit.moma.org/select">Tickets</a>
        </div>
        <div class="header_nav_lower">
            <span data-index="1">Visit</span> 
            <span data-index="2">What's on</span>  
            <span data-index="3">Art and artist</span>  
            <a href="https://store.moma.org/?_ga=2.93273949.1716260741.1710596028-1262709068.1710596028&_gl=1*d4f806*_ga*MTI2MjcwOTA2OC4xNzEwNTk2MDI4*_ga_8QY3201SLC*MTcxMDYwMTMzNy4yLjEuMTcxMDYwMzM1MS41MC4wLjA.&utm_source=moma.org">Store</a>   
            <img src="images\search_icon.png" />
            <div class="d1">
            <?php 
            if(isset($name_notfound)){
                echo "<h1>Hello, $userid</h1>";
            } else {
                echo "<h1>Hello, $nome</h1>";
            }
            ?>

                <img src="images\personal_area.jpeg"/>
            </div>
        </div>
    </header>
    <section id="menu_personal_area" class="hidden">
        <a href="http://localhost/hw1/personal_area.php">Personal Area</a>
        <a href="http://localhost/hw1/change_password.php">Change password</a>
        <a href="http://localhost/hw1/logout.php">Logout</a>
    </section>
    <section class='white hidden'>
        <div class='header_scroll hidden'>
            <div class="left">
                <span data-index="1">Visit</span> 
                <span data-index="2">What's on</span>  
                <span data-index="3">Art and artist</span>  
                <a href="https://store.moma.org/?_ga=2.93273949.1716260741.1710596028-1262709068.1710596028&_gl=1*d4f806*_ga*MTI2MjcwOTA2OC4xNzEwNTk2MDI4*_ga_8QY3201SLC*MTcxMDYwMTMzNy4yLjEuMTcxMDYwMzM1MS41MC4wLjA.&utm_source=moma.org">Store</a>   
                <img src="images\search_icon.png" />   
            </div>
            <div class="right">
                <span class='orange hotel'>Nearby Hotel</span>
                <a href="http://localhost/hw1/logout.php" class="orange">Logout</a>
                <a class="orange" href="https://visit.moma.org/select">Tickets</a>
            </div>
        </div>
    </section>
    <section id="book_a_tour" class="hidden"></section>
    <section id="custom_tour" class="hidden">
        <div id="close_custom_tour" class="close_button">CLOSE</div>
        <h1>Request a tour that is specifically designed for you, <br>
            based on your specific interests and duration.
        </h1>
        <form>
            <input type="text" spellcheck="false" placeholder="Make your request" id="chatgpt_text">
            <input type="submit" id="submit_chatgpt" value="&#8594;">   
        </form>
    </section>
    <section id="pop_up_m_v" class='pop_up_menu hidden'>
        <span>Tickets</span>
        <span>Locations,hours,and admission</span>
        <span>Map,audio,and more</span>
        <span>Where to start</span>
        <span class="custom_tour">Customized tours</span>
        <span>Frequently asked questions</span>
        <span class="tour">Book a tour</span>
        <span class="close">X</span>
    </section>
    <section id="pop_up_m_w" class='pop_up_menu hidden'>
        <span>In the galleries</span>
        <span>Events</span>
        <span>Film series</span>
        <span>Performance programs</span>
        <span>Exhibition history</span>
        <span class="close">X</span>
    </section>
    <section id="pop_up_m_a" class='pop_up_menu hidden'>
        <span>The Collection</span>
        <span>Artists</span>
        <span>Art terms</span>
        <span>Audio</span>
        <span>Magazine</span>
        <span class="close">X</span>
    </section>
    <section id="modal_view" class='modal_view hidden'>
        <form>
            <input type="text" spellcheck="false" placeholder="Search an artist on MoMA.org" class='text_box'>
            <input type="submit" id="submit_artist" value="">
        </form>
    </section>
    <section id="modal_view_artworks" class='hidden'>
        <div id="artworks_results">
        </div>
    </section>
    <section id="modal_view_hotel" class='hidden'>
        <h1>click esc to return</h1>
        <form>
            <input type="text" id="check_in" class="box" placeholder="check-in date YYYY-MM-DD">
            <input type="text" id="check_out" class="box" placeholder="check-out date YYYY-MM-DD">
            <input type="number" id="adult" class="box" placeholder="adults" min="1" step="1">
            <input type="number" id="rooms" class="box" placeholder="rooms" min="1" max= "4" step="1">
            <input type="submit" id="enter" value="&#8594;">
        </form>
        <div class="hotel_grid"></div>
    </section>
    <section id="container">
        <div class='section-box s1'>
            <div class='little l1'>
                <h1>An-My Le</h1>
                <span class="strong">Member Last Look, Mar17 </br>
                    Last chance</br>
                    Through Mar16
                </span>
            </div> 
            <img data-index="1" src="images\section_s1.webp"/> 
        </div>
        <div class='section-box s2'>
            <img data-index="2" src="images\section_s2.jpg"/>
            <div class='little l2'>
                <h1>Crafting</br>
                    Modernity:</br>
                    Design in Latin</br>
                    America,</br>
                    1940-1980
                </h1>
                <span class="strong">Through Sep 22</span>
            </div> 
        </div>
        <div class='section-box s3'>
            <div class='little l3'>
                <h1>Joan Jonas:</br>
                    Good Night</br>
                    Good Morning
                </h1>
                <span class="strong">Member Previews, Mar14-16</br>
                     Mar17-Jul6, 2024 
                </span>
                
            </div> 
            <img data-index="3" src="images\section_s3.jpg"/> 
        </div>
        <div class='section-box s4'>
            <img data-index="4" src="images\section_s4.jpg"/>
            <div class='little l4'>
                <h1>Shana Moulton:</br>
                    Meta/Physical</br>
                    Therapy
                </h1>
                <span class="strong">Through Apr 21</span>
            </div> 
        </div>
        <div class='section-box s5'>
            <div class='little l5'>
                <h1>Grace Wales</br>
                    Bonner
                </h1>
                <span class="strong">Now on view</span>
            </div> 
            <img data-index="5" src="images\section_s5.jpg"/> 
        </div>
        <div class='section-box s6'>
            <img data-index="6" src="images\section_s6.jpg"/>
            <div class='little l6'>
                <h1>Body Constructs</h1>
                <span class="strong">What happens when design</br>
                    collides with real people?
                </span>
            </div> 
        </div>
        <div class='section-box s7'>
            <div class='little l7'>
                <h1>Read, watch,</br>
                    and listen</br>
                    from wherever</br>
                    you are.
                </h1>
                <span class="strong">Explore Magazine</br>
                                    Online 
                </span>
            </div> 
            <img data-index="7" src="images\section_s7.jpg"/> 
        </div>
        <div class='section-box s8'>
            <img data-index="8" src="images\section_s8.jpg"/>
            <div class='little l8'>
                <h1>Visit MoMA</br>
                PS1 in Queens 
                </h1>
                <span class="strong">Free for New Yorkers</br>
                    MoMA PS1
                </span>
            </div> 
        </div>
    </section>
    <section id="home_location">
        <div class='home h1'>
            <span class="home_descr">MoMA</br>
                11 West 53 Street, Manhattan</br>
                Open today, 10:30 a.m.-5:30p.m.
            </span>
            <span class='home_descr d2'>Plan your visit &#8594;</span>
        </div>
        <div class='home h2'>
            <span class="home_descr">MoMA PS1</br>
                Visit MoMA PS1 in Queens</br>
                Free for New Yorkers
            </span>
            <span class='home_descr d2'>Learn more &#8599;</span>
        </div>
    </section>
    <section id="news_letter">
        <span class='newsl_descr n1'>Get art and ideas in your inbox</span>
        <span class='newsl_descr n2'>
            <input type="text" spellcheck="false" placeholder="Sign up for our newsletter" class="a" data-info="newsLetter"/>
            <div class="b"></div>
        </span>
    </section>
    <section id="magazine">
        <span class="mag1">Magazine</span>    
        <div class="mag2">
            <img src="images\magazine.jpeg"/>
            <div class="mag2_2">
                <span class="sent1">Drawn to MoMA</span>
                <h1>Manjit Thapp's Thaw Out</h1>
                <span class="sent2">The illustrator helps us look beyound our winter blues.</span>
                <span class="sent3">Manjit Thapp</span>
                <span class="sent4">Mar13, 2024</span>
            </div>
        </div>  
    </section>
    <section id="collection">
        <div class="title">In the collection</div>
        <img src="images\collection.jpg"/>
        <div class="subtitle">See what's on view &#8594;</div>    
    </section>
    <section id="orange">
        <div class="orange_figlio">
            <div class="o1">
                <span class="title">Discover more</br> 
                    as a member</span>
                <span class="subtitle">Join today &#8594;</span>
            </div> 
            <div class="o2">
                <img src="images\orange.gif"/>
            </div> 
        </div>  
    </section>
    <section id="store">
        <div class="store1">
            <div class="title">Store</div>
            <img src="images\store1.jpg"/>
            <div class="subtitle">Good Design in Living Color</div> 
        </div>
        <div class="store2">
            <div class="title">Store</div>
            <img src="images\store2.png"/>
            <div class="subtitle">Jean-Michel Basquiat Edition Polaroid</div> 
        </div>   
    </section>
    <section id="sponsor">
        <span class="title">Il MoMA desidera ringraziare i suoi partner principali.</span>
        <img src="images\sponsor.jpg"/>
    </section>
    <footer id="footer">
        <div class="footer_section">
            <div class="left">
                <span class="word">About us</span>
                <span class="word">Support</span>
                <span class="word">Research</span>
                <span class="word">Teaching</span>
                <span class="word">Magazine</span>
            </div>
            <a href="http://localhost/hw1/logout.php" class='word right'>Logout</a>
        </div>
        <div class="footer_address">
            <div class="left">
                <span class="word">MoMA</br>
                    11 West 53 Street, Manhattan</br>
                    Open today, 10:30 a.m.-5:30 p.m.
                </span>
                <div class="social">
                    <img class="icon" src="images\instagram.png"/>
                    <img class='icon f' src="images\facebook.png"/>
                    <img class="icon" src="images\mail.png"/>
                    <img class="icon" src="images\tiktok.png"/>
                    <img class='icon s' src="images\spotify.png"/>
                    <img class="icon" src="images\youtube.png"/>
                  
                </div>
            </div>
            <span class='word right'>MoMA PS1</br>
                Visit MoMA PS1 in Queens</br>
                Free for New Yorkers
            </span>   
        </div>
        <div class="footer_mail">
            <input type="text" spellcheck="false" placeholder="Art and ideas in your inbox" class='word a' data-info="footer"/>
            <div class="line"></div>   
        </div>
        <div class="footer_info">
            <span class="title">MoMA</span>
            <span class="info">
                <span class="word">Privacy policy</span>
                <span class="word">Terms of use</span>
                <span class="word">Visitor guidelines and policies</span>
                <span class="word">Reset text contrast</span>
            </span>
            <span class="trademark">© 2024 The Museum of Modern Art</span>
        </div>
    </footer>
  </body>
 </html>