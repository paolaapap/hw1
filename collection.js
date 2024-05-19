const fixHeader = document.querySelector('#fix_header');
const dinamicHeader = document.querySelector('#dinamic_header');
const dataListFilter = document.querySelector('#filters');
const filterInput = document.querySelector('#filter_input');
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
    fetch("fetch_category_collection.php").then(fetchResponse).then(fetchCategoryJson);
    fetch("fetch_collection.php").then(fetchResponse).then(fetchArtworksJson); //la fetch è uguale per entrambe
}

function fetchResponse(response) {
    if (!response.ok) {return null};
    return response.json();
}

function fetchCategoryJson(json){
    if(json.length == 0){
        return;
    }
    dataListFilter.innerHTML = '';
    for (let result in json){
        const op = document.createElement("option");
        op.value=json[result].category;
        dataListFilter.appendChild(op);
    }
}

filterInput.addEventListener("change", fetchSearchByCategory);

function fetchSearchByCategory(event){
    fetch("fetch_bycategory.php?category=" +encodeURIComponent(event.target.value)).then(fetchResponse).then(fetchArtworksJson);
}

function fetchArtworksJson(json){
    console.log(json);
    if(json.length == 0){
        noResults(artworksSection);
        return;
    }

    filterInput.value='';
    artworksSection.innerHTML = '';
    for (let result in json){
        const div = document.createElement("div");
        div.classList.add('artworks');
        div.dataset.index = json[result].id;
        artworksSection.appendChild(div);
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