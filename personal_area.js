
const fixHeader = document.querySelector('#fix_header');
const dinamicHeader = document.querySelector('#dinamic_header');
const collection_figlio = document.querySelector('#collection_figlio');
const auction_figlio = document.querySelector('#auction_figlio');
const ongoing_figlio = document.querySelector('#ongoing_figlio');
const headerForNotif = document.querySelector('.header_nav_lower_right');
const notifSection = document.querySelector('#notifications');
const tour_figlio = document.querySelector('#tour_figlio');

fetch_all();
setInterval(notifications, 5000); //permette di eseguire il codice a intervalli regolari.
setInterval(expires, 5000); //permette di eseguire il codice a intervalli regolari.

function fetch_all(){
    fetch("fetch_favourites.php").then(fetchResponse).then(fetchCollectionJson); 
    fetch("fetch_auction.php?user_id=true").then(fetchResponse).then(fetchAuctionJson); 
    fetch("fetch_myoffers.php").then(fetchResponse).then(fetchOffersJson);
    fetch("fetch_notifications.php").then(fetchResponse).then(fetchNotificationJson);
    fetch("fetch_saved_unsaved_tour.php").then(fetchResponse).then(fetchTourJson);
}

function notifications(){
    fetch("fetch_notifications.php").then(fetchResponse).then(fetchNotificationJson);    
}

function expires(){
    fetch("fetch_check_expires.php").then(fetchResponse).then(fetchExpiresJson);
}

function fetchExpiresJson(json){
    if(json.ok){
        fetch_all();
    }
}

function checkScrolling(event)
{
    let scroll = document.documentElement.scrollTop;
    if(scroll > 0) 
    {   
        dinamicHeader.classList.remove('hidden');
        fixHeader.classList.add('hidden');
        
    }
    else
    {   
        dinamicHeader.classList.add('hidden');
        fixHeader.classList.remove('hidden');
    }
}

document.addEventListener('scroll',checkScrolling);

function fetchResponse(response) {
    if (!response.ok) {return null};
    return response.json();
}

function fetchCollectionJson(json){
    if(json.res == "false"){
        noResults(collection_figlio);
        return;
    }

    collection_figlio.innerHTML = '';
    for (let result in json){
        const div = document.createElement("div");
        div.classList.add('artworks');
        div.dataset.index = json[result].id;
        collection_figlio.appendChild(div);
        const img = document.createElement("img");
        img.src=json[result].content.image;
        div.appendChild(img);
        const author = document.createElement("span");
        author.textContent= json[result].content.author;
        div.append(author);
        div.addEventListener("click", show_collection_details);
    }
}

function show_collection_details(event){
    window.location.href = 'info_collection.php?id='+encodeURIComponent(event.currentTarget.dataset.index);
}

function noResults(father){
    father.innerHTML='';
    const span_error = document.createElement('span');
    span_error.textContent = 'No results found.'
    father.appendChild(span_error);
}

function fetchAuctionJson(json){
    if(json.lenght == 0){
        noResults(auction_figlio);
        return;
    }  
    
    auction_figlio.innerHTML='';
    for (let result in json){
        const div = document.createElement("div");
        div.classList.add('artworks');
        div.dataset.index = json[result].asta_id;
        auction_figlio.appendChild(div);
        const path = json[result].foto;
        const src = path.substring(path.indexOf("uploads")); //trova la posizione iniziale della sottostringa "uploads" utilizzando il metodo indexOf(), utilizzando substring(), estrarrà la parte della stringa da quella posizione fino alla fine
        const img = document.createElement("img");
        img.src= src;
        div.appendChild(img);
        const title = document.createElement("span");
        title.textContent= json[result].titolo;
        div.append(title);
        div.addEventListener("click", show_auction_details);
    }
}

function show_auction_details(event){
    window.location.href = 'info_auction.php?id='+encodeURIComponent(event.currentTarget.dataset.index);
}

function fetchOffersJson(json){
    if(json.lenght == 0){
        noResults(ongoing_figlio);
        return;
    }  
    
    ongoing_figlio.innerHTML='';
    for (let result in json){
        const div = document.createElement("div");
        div.classList.add('artworks');
        div.dataset.index = json[result].asta_id;
        ongoing_figlio.appendChild(div);
        const path = json[result].foto;
        const src = path.substring(path.indexOf("uploads")); //trova la posizione iniziale della sottostringa "uploads" utilizzando il metodo indexOf(), utilizzando substring(), estrarrà la parte della stringa da quella posizione fino alla fine
        const img = document.createElement("img");
        img.src= src;
        div.appendChild(img);
        const title = document.createElement("span");
        title.textContent= json[result].titolo;
        div.append(title);
        div.addEventListener("click", show_auction_details);
    }

}

function fetchNotificationJson(json){
    if(json.length==0){
        notifSection.innerHTML='';
        notifSection.classList.add('no_notif');
        if(headerForNotif.querySelector('img')){
            const img = headerForNotif.querySelector('img');
            //headerForNotif.removeChild(img);
            img.src = 'images/no_notifications.jpg';
        } else {
            const new_img = document.createElement('img');
            new_img.src = 'images/no_notifications.jpg';
            headerForNotif.appendChild(new_img);
        }
    
    } else {
        notifSection.classList.remove('no_notif');
        if(headerForNotif.querySelector('img')){
            const img = headerForNotif.querySelector('img');
            //headerForNotif.removeChild(img);
            img.src = 'images/yes_notifications.jpg';
            img.addEventListener('mouseenter', showPersonalMenu);
            img.addEventListener('mouseleave', hidePersonalMenu);
        } else {
            const new_img = document.createElement('img');
            new_img.src = 'images/yes_notifications.jpg';
            headerForNotif.appendChild(new_img);
            new_img.addEventListener('mouseenter', showPersonalMenu);
            new_img.addEventListener('mouseleave', hidePersonalMenu);
            notifSection.addEventListener('mouseleave', hidePersonalMenu);
        }

        notifSection.innerHTML='';
        for (let result in json){
            const span = document.createElement('span');
            span.textContent = json[result].content;
            notifSection.appendChild(span);
        }

        const div = document.createElement('div');
        div.textContent = 'Hide all';
        notifSection.classList.add('pointer');
        notifSection.appendChild(div);
        notifSection.addEventListener("click", hide_all);

        function hide_all(){
            fetch("fetch_notifications.php?hide=true").then(fetchResponse).then(fetchNotificationJson);    
        }

        function showPersonalMenu(){
            notifSection.classList.remove('hidden');
        }

        function hidePersonalMenu(event){
            // Nascondo il menu solo se il mouse non è sopra il menu stesso o sopra l'elemento che lo attiva
            if (!event.currentTarget.contains(event.relatedTarget) && !notifSection.contains(event.relatedTarget)) {
                notifSection.classList.add('hidden');
            }
        }
    }
}

function fetchTourJson(json){

    tour_figlio.innerHTML = '';
    for (let result in json){

        const div = document.createElement("div");
        div.classList.add('artworks');
        div.dataset.index = json[result].tour_id;
        tour_figlio.appendChild(div);

        const img = document.createElement("img");
        img.src=json[result].content.image;
        div.appendChild(img);

        const link = document.createElement("a");
        link.href = json[result].content.link;
        link.textContent = json[result].content.title;
        div.append(link);

        const remove = document.createElement("h1");
        remove.classList.add("remove_button");
        remove.textContent = "Unsaved";
        div.appendChild(remove);

        remove.addEventListener("click", remove_tour);
    }
}

function remove_tour(event){
    const id_remove = event.currentTarget.parentNode.dataset.index;
    fetch("fetch_saved_unsaved_tour.php?id_remove=" + id_remove).then(fetchResponse).then(fetchRemoveTourJson);
}

function fetchRemoveTourJson(json){
    if(json.ok){
        fetch_all();
    } else {
        console.log("Errore");
    }
}