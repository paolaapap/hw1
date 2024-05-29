//problema di flex-shrink 
//metti i controlli nel caso che le api non funzionino


const navClick = document.querySelectorAll('#header_nav_lower span');
const popUpMenu = document.querySelectorAll('.pop_up_menu');
const closeButton = document.querySelectorAll('.close');
const personalAreaH1 = document.querySelector('#header_nav_lower .profile h1');
const personalAreaImg = document.querySelector('#header_nav_lower .profile img');
const menuPersonalArea = document.querySelector("#menu_personal_area");
const exib_container = document.querySelector('#exibitions');
const exib_img_dinamic = [];
const exib_img_static = [];
const bookATour = document.querySelector('.tour');
const header_scroll = document.querySelector('#header_scroll');
const header_fix = document.querySelector('header');
const navClickScroll = document.querySelectorAll('#header_scroll span');
const magazine = document.querySelector('#magazine .mag2');
const search_icon = document.querySelector('#header_nav_lower img');
const searchArtistSection = document.querySelector('#search_artist');
const searchArtistForm = document.querySelector('#search_artist form');
const textBox = document.querySelector('.text_box');
const searchIconScroll = document.querySelector ('#header_scroll img');
const textBoxMail = document.querySelectorAll('.a');
const sponsor = document.querySelector('#sponsor');
const collection = document.querySelector('#collection');
const form_hotel = document.querySelector('#modal_view_hotel form');
const modalViewHotel = document.querySelector('#modal_view_hotel');
const modalViewArtworks = document.querySelector('#modal_view_artworks');
const artworkResults = document.querySelector('#artworks_results');
const hotel_grid = document.querySelector('.hotel_grid');
const nearby_hotel_click = document.querySelectorAll('.hotel');
const bookATour_section = document.querySelector('#book_a_tour');
let source = [
    "GetYourGuide",
    "Viator",
    "The Reninsula Hotels",
    "Civitatis",
    "New York Tours Plus",
    "The Men Event",
    "Headout",
    "Klook",
    "Expedia.com",
    "www.tourappeal.com",
    "Trip Savvy",
    "Tripadvisor"
];


function stopProp(event){   
    event.stopPropagation();
}

function fetch_all(){
    fetch("fetch_images.php?section=exibition").then(fetchResponse).then(fetchExibitionsJson);
    fetch("fetch_images.php?section=magazine").then(fetchResponse).then(fetchMagazineJson);
    fetch("fetch_images.php?section=sponsor").then(fetchResponse).then(fetchSponsorJson);
    fetch("fetch_collection.php").then(fetchResponse).then(fetchCollectionJson);
}

fetch_all();

function fetchResponse(response) {
    if (!response.ok) {return null};
    return response.json();
}

function noResults(father){
    father.innerHTML='';
    const span_error = document.createElement('span');
    span_error.textContent = 'No results found.'
    father.appendChild(span_error);
}

function fetchExibitionsJson(json){
    if(json.length == 0){
        noResults(exib_container);
        return;
    }

    exib_container.innerHTML = '';
    for (let result in json){
        const id = json[result].id;
        const class_box = "s" + id;
        const little_box = "l" + id;
        const sectionBox = document.createElement('div');
        sectionBox.classList.add('section-box');
        sectionBox.classList.add(class_box);
        exib_container.appendChild(sectionBox);
        const little = document.createElement('div');
        little.classList.add('little');
        little.classList.add(little_box);
        const img = document.createElement('img');
        img.dataset.index = id;
        const h1 = document.createElement('h1');
        const span = document.createElement('span');
        span.classList.add('strong');
        little.appendChild(h1);
        little.appendChild(span);
        h1.textContent = json[result].content.title;
        span.textContent = json[result].content.text;
        img.src = json[result].content.image;
        exib_img_dinamic[id] = json[result].content.image_dinamic;
        exib_img_static[id] = json[result].content.image;
        img.addEventListener('mouseenter', changeImg);
        img.addEventListener('mouseleave', resetImg);
        if((id % 2) === 0 ){ //se l'id è pari
            sectionBox.appendChild(img);
            sectionBox.appendChild(little);
        } else {
            sectionBox.appendChild(little);
            sectionBox.appendChild(img);
        }

    }

}


//funzione generatrice di un intero random tra 0 e index-1
function generaNumeroRandomico(index) {
    return Math.floor(Math.random() * index) ;
}

function fetchMagazineJson(json){
    if(json.length == 0){
        noResults(magazine);
        return;
    }

    magazine.innerHTML = '';
    let index = generaNumeroRandomico(json.length);
    const img = document.createElement("img");
    img.src = json[index].content.image;
    magazine.appendChild(img);
    const div = document.createElement("div");
    div.classList.add("mag2_2");
    magazine.appendChild(div);
    const title1 = document.createElement("h1");
    title1.textContent=json[index].content.title1;
    title1.classList.add("title1");
    div.appendChild(title1);
    const title2 = document.createElement("h1");
    title2.textContent=json[index].content.title2;
    div.appendChild(title2);
    const desc = document.createElement("span");
    desc.textContent=json[index].content.description;
    desc.classList.add("span1");
    div.appendChild(desc);
    const auth_and_date = document.createElement("span");
    auth_and_date.textContent=json[index].content.author_and_date;
    auth_and_date.classList.add("span2");
    div.appendChild(auth_and_date);
}



function fetchSponsorJson(json){
    if(json.length == 0){
        noResults(sponsor.img);
        return;
    }
    let index = generaNumeroRandomico(json.length);
    const img = document.createElement("img");
    img.src = json[index].content.image;
    sponsor.appendChild(img);

}


function fetchCollectionJson(json){
    if(json.length == 0){
        noResults(collection.img);
        return;
    }
    let index = generaNumeroRandomico(json.length);
    const img = document.createElement("img");
    img.src = json[index].content.image;
    img.dataset.index = json[index].id;
    const div = collection.querySelector('.title');
    div.insertAdjacentElement('afterend', img);
    img.addEventListener("click", show_collection_details);
}

function show_collection_details(event){
    window.location.href = 'info_collection.php?id='+encodeURIComponent(event.currentTarget.dataset.index);
}


function showMenu (event){
    const index = parseInt(event.currentTarget.dataset.index);
    for (p of popUpMenu){
        p.classList.add('hidden');
    }
    document.body.classList.add('no-scroll');
    switch(index){
        case 1:
            const popUpMenu1 = document.querySelector('#pop_up_m_v');
            popUpMenu1.classList.remove('hidden');
            break;
        case 2:
            const popUpMenu2 = document.querySelector('#pop_up_m_w');
            popUpMenu2.classList.remove('hidden');
            break;
        case 3:
            const popUpMenu3 = document.querySelector('#pop_up_m_a');
            popUpMenu3.classList.remove('hidden');
            break;
    }
}

for (n of navClick){
    n.addEventListener('click', showMenu);
}

for (n of navClickScroll){
    n.addEventListener('click', showMenu);
}

function hideMenu(){
    for (const p of popUpMenu){
        p.classList.add('hidden');
    }
    document.body.classList.remove('no-scroll');
}

for (const c of closeButton){
    c.addEventListener('click', hideMenu);    
}

if(document.querySelector(".profile img")){
    function showPersonalMenu(){
        menuPersonalArea.classList.remove('hidden');
    }

    personalAreaH1.addEventListener('mouseenter', showPersonalMenu);
    personalAreaImg.addEventListener('mouseenter', showPersonalMenu);

    function hidePersonalMenu(event){
        // Nascondo il menu solo se il mouse non è sopra il menu stesso o sopra l'elemento che lo attiva
        //nel caso di mouseenter e mouseleave event.relatedTarget sarà l'elemento da cui il puntatore del mouse si sta spostando
        //verifico se l'elemento che ha ricevuto il focus è contenuto in menuPersonalArea, personalAreaH!, personalAreaImg
        if (!menuPersonalArea.contains(event.relatedTarget) && !personalAreaH1.contains(event.relatedTarget) && !personalAreaImg.contains(event.relatedTarget)) {
            menuPersonalArea.classList.add('hidden');
        }
    }

    personalAreaH1.addEventListener('mouseleave', hidePersonalMenu);
    personalAreaImg.addEventListener('mouseleave', hidePersonalMenu);
    menuPersonalArea.addEventListener('mouseleave', hidePersonalMenu);
}

function changeImg(event) {
    const image = event.currentTarget;
    const index = parseInt(image.dataset.index);
    switch (index) {
        case 1:
            image.src = exib_img_dinamic[1];
            break;
        case 2:
            image.src = exib_img_dinamic[2];
            break;
        case 3:
            image.src = exib_img_dinamic[3];
            break;
        case 4:
            image.src = exib_img_dinamic[4];
            break;
        case 5:
            image.src = exib_img_dinamic[5];
            break;
        case 6:
            image.src = exib_img_dinamic[6];
            break;
        case 7:
            image.src = exib_img_dinamic[7];
            break;
        case 8:
            image.src = exib_img_dinamic[8];
            break;
    }
}

function resetImg(event) {
    const image = event.currentTarget;
    const index = parseInt(image.dataset.index);
    switch (index) {
        case 1:
            image.src = exib_img_static[1];
            break;
        case 2:
            image.src = exib_img_static[2];
            break;
        case 3:
            image.src = exib_img_static[3];
            break;
        case 4:
            image.src = exib_img_static[4];
            break;
        case 5:
            image.src = exib_img_static[5];
            break;
        case 6:
            image.src = exib_img_static[6];
            break;
        case 7:
            image.src = exib_img_static[7];
            break;
        case 8:
            image.src = exib_img_static[8];
            break;
    }
}



function checkScrolling(event)
{
    let scroll = document.documentElement.scrollTop;
    if(scroll > 0) 
    {   
        header_scroll.classList.remove('hidden');
        header_fix.classList.add('hidden');
        
    }
    else
    {   
        header_scroll.classList.add('hidden');
        header_fix.classList.remove('hidden');
    }
}

document.addEventListener('scroll',checkScrolling);

function showSearchBar(){
    searchArtistSection.classList.remove('hidden');
    document.body.classList.add('no-scroll');
}

search_icon.addEventListener('click', showSearchBar);
searchIconScroll.addEventListener('click', showSearchBar);

function hideSearchBar(event){
    searchArtistSection.classList.add('hidden');
    document.body.classList.remove('no-scroll');
}

searchArtistSection.addEventListener('click', hideSearchBar);

textBox.addEventListener('click', stopProp);

function storeMail (event){
    const type=event.currentTarget.dataset.info;
    if(event.key === 'Enter'){
        event.currentTarget.value='';
        if(type === 'newsLetter'){
            event.currentTarget.placeholder="You are now subscribed.";
        }
        else {
            event.currentTarget.placeholder="We'll keep you posted!";
        }
    event.currentTarget.disabled = true;
    event.currentTarget.removeEventListener('keypress', storeMail);
    }
}

for(t of textBoxMail){  
    t.addEventListener('keypress', storeMail);
}

///////////////////////////////////////////////////////// API HOTEL ///////////////////////////

function onHotelJson(json) {

    hotel_grid.innerHTML='';
    const status = json.status;
    if(status === false){
        const err_message = document.createElement("h1");
        err_message.textContent = 'No results found for this period.';
        err_message.classList.add('error_message');
        modalViewHotel.appendChild(err_message);
    }
    
    else{
        const list_result = json.data.data;
        for(result of list_result){
            const title = result.title;
            const rating = result.bubbleRating.rating;
            const price_number = result.priceForDisplay;
            const price = String(price_number);
            const provider = result.provider;
            const photo_list = result.cardPhotos;  
            const photo_full= photo_list[0].sizes.urlTemplate;
            const photo_array = photo_full.split('?');
            const photo = photo_array [0];
            const url_booking = result.commerceInfo.externalUrl;
            //aggiungo gli elementi estratti dalla query
            const hotel_box = document.createElement("div");
            hotel_grid.appendChild(hotel_box);
            hotel_box.classList.add('single_hotel');

            const h1 = document.createElement("h1");
            h1.textContent=title;
            const span1 = document.createElement("span");
            span1.textContent= 'rating: ' + rating;
            const img = document.createElement("img");
            img.src=photo;
            const span2 = document.createElement("span");
            span2.textContent= 'provider: ' + provider;
            const span3 = document.createElement("span");
            span3.textContent= 'price: ' + price;
            const link = document.createElement("a");
            link.href=url_booking;
            link.textContent='book now';

            
            hotel_box.appendChild(h1);
            hotel_box.appendChild(span1);
            hotel_box.appendChild(img);
            hotel_box.appendChild(span2);
            hotel_box.appendChild(span3);
            hotel_box.appendChild(link);
            span1.classList.add('shrink');
            img.classList.add('shrink');
            span2.classList.add('shrink');
            span3.classList.add('shrink');
            link.classList.add('shrink');
            
        }
    }
}
  

function onResponse(response) {
    if (!response.ok) {
      console.log("Error: " + response);
      return null;
    } else return response.json();
}

function search(event) {
    event.preventDefault();
    const check_in = document.querySelector("#check_in");
    const check_out = document.querySelector('#check_out');
    const adults = document.querySelector('#adult');
    const rooms = document.querySelector('#rooms');
    const check_in_value = encodeURIComponent(check_in.value);
    const check_out_value = encodeURIComponent(check_out.value);
    const adults_value = encodeURIComponent(adults.value);
    const rooms_value = encodeURIComponent(rooms.value);
  
    const url = 'fetch_api_hotel.php?check_in=' + check_in_value + '&check_out=' + check_out_value + '&adults=' + adults_value + '&room=' + rooms_value;
    fetch(url).then(onResponse).then(onHotelJson);
}

form_hotel.addEventListener('submit', search);

function modalHotel(){
    modalViewHotel.classList.remove('hidden');
    document.body.classList.remove('no-scroll'); //perche sui click nella navbar c'è una funzione che leva lo scroll
}

for (n of nearby_hotel_click) { 
    n.addEventListener('click', modalHotel);
}

function hideModalHotel (event){
    if(event.key === "Escape"){
        modalViewHotel.classList.add('hidden');
        document.body.classList.remove('no-scroll'); //perche sui click nella navbar c'è una funzione che leva lo scroll
        const input = document.querySelectorAll('#modal_view_hotel .box');
        for(i of input)
            i.value="";
        hotel_grid.innerHTML='';
    }

}

document.addEventListener('keydown', hideModalHotel);

////////////////// API CON OAUTH - ARTSY ///////////////////////////////////////////

searchArtistForm.addEventListener('submit', searchArtist);

let user_input = '';

function searchArtist(event){
    searchArtistSection.classList.add('hidden');
    modalViewArtworks.classList.remove('hidden');
    event.preventDefault();
    user_input = textBox.value;
    fetch("fetch_api_artsy.php?artist_name=" + user_input).then(onResponse).then(onJsonArtist);
}

function onJsonArtist(json){
    if(json.message == "Artist Not Found"){
        textBox.value="";
        artworkResults.innerHTML='';
        const h1 = document.createElement("h1");
        h1.textContent = "Artist Not Found";
        artworkResults.appendChild(h1);
    } else {
        const link = json._links.location.href;
        const parts = link.split('/');
        const artist_id = parts[parts.length - 1];
        fetch("fetch_api_artsy.php?artist_id=" + artist_id).then(onResponse).then(onJsonArtworks);
    }
}

function onJsonArtworks(json){

    if(json.message == "Artist Not Found" || json._embedded.artworks.length == 0){
        textBox.value="";
        artworkResults.innerHTML='';
        const h1 = document.createElement("h1");
        h1.textContent = "Artist Not Found";
        artworkResults.appendChild(h1);

    } else {

        textBox.value="";
        artworkResults.innerHTML='';

        if(modalViewArtworks.querySelector('h1')){
            const h1 = modalViewArtworks.querySelector('h1');
            h1.remove();
        }

        const name = document.createElement("h1");
        name.textContent=(user_input.toUpperCase());
        modalViewArtworks.insertBefore(name, artworkResults);
        
        const artworks = json._embedded.artworks;
        const thum_src = [];
        const titles = [];

        for (let i=0; i<artworks.length; i++){
                thum_src[i] = artworks[i]._links.thumbnail.href;
                titles[i]=artworks[i].title;
        }
        
        for(let i=0; i<thum_src.length; i++){

            const new_div = document.createElement("div");
            artworkResults.appendChild(new_div);
            new_div.classList.add('artworks_and_title');

            const img = document.createElement("img");
            img.src=thum_src[i];
            new_div.appendChild(img);

            const title = document.createElement("span");
            title.textContent=titles[i];
            new_div.appendChild(title);
        } 

        document.body.classList.add('no-scroll');
        modalViewArtworks.classList.add('scroll');
    }
}


function hideArtworks(event){
    modalViewArtworks.classList.add('hidden');
    document.body.classList.remove('no-scroll');
    modalViewArtworks.classList.remove('scroll');
}

modalViewArtworks.addEventListener('click', hideArtworks);
artworkResults.addEventListener('click', stopProp);

////////////////////// API JOJ ////////////////////////////

bookATour.addEventListener('click', searchTour);

function searchTour(event) {
    fetch("fetch_api_tour.php").then(onResponse).then(onTourJson);
}


function onTourJson(json) {
    for (p of popUpMenu)
        p.classList.add('hidden');
    bookATour_section.classList.remove('hidden');
    document.body.classList.add('no-scroll');
    bookATour_section.classList.add('scroll');
    bookATour_section.innerHTML='';
    const result = json.response.images;
    const titles = [];
    const img = [];
    const provider = [];
    const link = [];
    let j = 0;
    for (r of result){
        for (let i=0; i<source.length; i++){
            if(r.source.name==source[i]){
                //prendi i campi
                titles[j]=r.source.title;
                img[j]=r.image.url;
                link[j]=r.source.page;
                provider[j]=r.source.name;
                j++;
                break;
            }
            else i++;
        }
        
    }

    // se ha trovato la stessa cosa piu volte, la rimuovo
    for(let i=0; i<(link.length-1); i++){
        if(link[i]==link[i+1]){
            titles.splice(i,1);
            img.splice(i,1);
            provider.splice(i,1);
            link.splice(i,1);
        }
    }

    const close_button = document.createElement("span");
    close_button.textContent="CLOSE";
    bookATour_section.appendChild(close_button);
    close_button.classList.add('close_button');

    for(let i=0; i<titles.length; i++){
        const div = document.createElement("div");
        bookATour_section.appendChild(div);
        const t = document.createElement("h1");
        div.appendChild(t);
        const div2 = document.createElement("div");
        div.appendChild(div2);
        div2.classList.add('div2');
        const im = document.createElement("img");
        div2.appendChild(im);
        const div3 = document.createElement("div");
        div2.appendChild(div3);
        div3.classList.add('div3');
        const l = document.createElement("a");
        div3.appendChild(l);
        const p = document.createElement("span");
        div3.appendChild(p);
        const star = document.createElement("span");
        star.classList.add("pref_tour");
        star.addEventListener("click", saveTour);
        div3.appendChild(star);


        t.textContent=titles[i];
        im.src=img[i];
        p.textContent="by: " + provider[i];
        l.href=link[i];
        l.textContent="Reserve now";
        star.textContent = "Save it for later";

        div.dataset.index = i;
        div.dataset.title = titles[i];
        div.dataset.img = img[i];
        div.dataset.link = link[i];

    }

    close_button.addEventListener('click', close_tour);
}

function close_tour(){
    bookATour_section.classList.add('hidden');
    document.body.classList.remove('no-scroll');
    bookATour_section.classList.remove('scroll');
}

function saveTour(event){
    const tour = event.currentTarget.parentNode.parentNode.parentNode;
    const formData = new FormData();

    formData.append('id', tour.dataset.index);
    formData.append('title', tour.dataset.title);
    formData.append('img', tour.dataset.img);
    formData.append('link', tour.dataset.link);

    fetch("fetch_save_tour.php", {method: 'post', body: formData}).then(onResponse).then(onAddTourJson);
    
}


function onAddTourJson(json){
    console.log(json);
    if(!json.ok){   
       if(json.error == "Utente non autenticato"){
            window.location.href = 'login.php';
       }
    } 
}



