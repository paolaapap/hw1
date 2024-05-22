const fixHeader = document.querySelector('#fix_header');
const dinamicHeader = document.querySelector('#dinamic_header');
const artworksSection = document.querySelector('#artworks_section');

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

function fetch_all(){
    fetch("fetch_auction.php").then(fetchResponse).then(fetchArtworksJson); 
}

function fetchResponse(response) {
    if (!response.ok) {return null};
    return response.json();
}

function fetchArtworksJson(json){
    if(json.length == 0){
        noResults(artworksSection);
        return;
    }

    artworksSection.innerHTML = '';
    for (let result in json){
        const div = document.createElement("div");
        div.classList.add('artworks');
        div.dataset.index = json[result].asta_id;
        artworksSection.appendChild(div);
        const path = json[result].foto;
        const src = path.substring(path.indexOf("uploads")); //trova la posizione iniziale della sottostringa "uploads" utilizzando il metodo indexOf(), utilizzando substring(), estrarrà la parte della stringa da quella posizione fino alla fine
        const img = document.createElement("img");
        img.src= src;
        div.appendChild(img);
        const title = document.createElement("span");
        title.textContent= json[result].titolo;
        div.append(title);
        const deadline = document.createElement("span");
        deadline.textContent = "Deadline: " + json[result].durata;
        deadline.classList.add("deadline");
        div.appendChild(deadline);
        div.addEventListener("click", show_details);
    }
}

function show_details(event){
    window.location.href = 'info_auction.php?id='+encodeURIComponent(event.currentTarget.dataset.index);
}
