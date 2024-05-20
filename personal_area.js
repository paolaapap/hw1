const fixHeader = document.querySelector('#fix_header');
const dinamicHeader = document.querySelector('#dinamic_header');
const collection_section = document.querySelector('#collection');
const collection_figlio = document.querySelector('#collection_figlio');

fetch_all();

function fetch_all(){
    fetch("fetch_favourites.php").then(fetchResponse).then(fetchCollectionJson);   
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
    }
}

function noResults(father){
    father.innerHTML='';
    const span_error = document.createElement('span');
    span_error.textContent = 'No results found.'
    father.appendChild(span_error);
}