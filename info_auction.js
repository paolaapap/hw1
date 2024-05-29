const fixHeader = document.querySelector('#fix_header');
const dinamicHeader = document.querySelector('#dinamic_header');
const divImage = document.querySelector('#image');
const divDetails = document.querySelector('#details');
const auction_id = document.querySelector('#show_auction').dataset.index;

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

fetch_all();
setInterval(fetch_all, 5000);
setInterval(expires, 5000); //permette di eseguire il codice a intervalli regolari.

function fetch_all(){
    fetch("fetch_auction.php?id="+auction_id).then(fetchResponse).then(fetchAuctionJson);
}

function expires(){
    fetch("fetch_check_expires.php").then(fetchResponse).then(fetchExpiresJson);
}

function fetchExpiresJson(json){
    if(json.ok){
        fetch_all();
    }
}

function fetchResponse(response) {
    if (!response.ok) {return null};
    return response.json();
}


let old_price = 0;

function fetchAuctionJson(json){
    if(!json[0].res){
        window.location.href="running_auction.php";
        return;
    }

    divImage.innerHTML = '';
    divDetails.innerHTML = '';
    const path = json[0].foto;
    const src = path.substring(path.indexOf("uploads")); //trova la posizione iniziale della sottostringa "uploads" utilizzando il metodo indexOf(), utilizzando substring(), estrarrà la parte della stringa da quella posizione fino alla fine
    const img = document.createElement("img");
    img.src= src;
    divImage.appendChild(img);
    
    const titolo = document.createElement("h1");
    titolo.textContent = json[0].titolo;
    divDetails.appendChild(titolo);

    const durata = document.createElement("span");
    durata.textContent = "Deadline: " + json[0].durata;
    durata.classList.add("deadline");
    divDetails.appendChild(durata);

    const num_offerte = document.createElement("span");
    num_offerte.textContent = "Offers from other users: " + json[0].num_offerte;
    divDetails.appendChild(num_offerte);

    const prezzo_iniziale = document.createElement("span");
    prezzo_iniziale.textContent = "Starting price: " + json[0].prezzo_iniziale + "$";
    divDetails.appendChild(prezzo_iniziale);

    const ultimo_prezzo = document.createElement("span");
    ultimo_prezzo.textContent = "Latest price: " + json[0].ultimo_prezzo + "$";
    divDetails.appendChild(ultimo_prezzo);
    //se il prezzo cambia lo faccio lampeggiare 
    if(old_price < json[0].ultimo_prezzo){
        ultimo_prezzo.classList.add("lampeggiante");
        old_price = json[0].ultimo_prezzo;
    }

    const form = document.createElement("form");
    form.name = "offers_form";
    form.method = "post"
    const input = document.createElement("input");
    input.type = "text";
    input.name = "offers";
    input.placeholder = "Make an offer now!"
    form.appendChild(input);
    divDetails.appendChild(form);
}