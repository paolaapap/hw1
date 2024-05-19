<!DOCTYPE html>
 <html>
  <head>
    <meta charset="utf-8">
      <title>INDEX</title>
      <link rel="stylesheet" href="index.css"/>
      <script src="index.js" defer></script>
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
            <a class="a1" href="http://localhost/hw1/login.php">Membership</a>
            <a class="a2" href="https://visit.moma.org/select">Tickets</a>
        </div>
        <div class="header_nav_lower">
            <span data-index="1">Visit</span> 
            <span data-index="2">What's on</span>  
            <span data-index="3">Art and artist</span>  
            <a href="https://store.moma.org/?_ga=2.93273949.1716260741.1710596028-1262709068.1710596028&_gl=1*d4f806*_ga*MTI2MjcwOTA2OC4xNzEwNTk2MDI4*_ga_8QY3201SLC*MTcxMDYwMTMzNy4yLjEuMTcxMDYwMzM1MS41MC4wLjA.&utm_source=moma.org">Store</a>   
            <img src="images\search_icon.png" />
            <div class="d1"></div>
        </div>
    </header>
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
                <a href="http://localhost/hw1/login.php" class="orange">Membership</a>
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
        <a href="http://localhost/hw1/collection.php">The Collection</a>
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
    <section id="exibitions"></section>
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
        <h1 class="mag1">Magazine</h1>
        <div class="mag2">

        </div>
    </section>
    <section id="collection">
        <div class="title">In the collection</div>
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
        <span class="title">MoMA gratefully acknowledges its major partners.</span>
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
            <a href="http://localhost/hw1/login.php" class='word right'>Log in</a>
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