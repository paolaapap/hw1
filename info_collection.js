const fixHeader = document.querySelector('#fix_header');
const dinamicHeader = document.querySelector('#dinamic_header');
const divImage = document.querySelector('#image');
const divDetailsLeft = document.querySelector('#details .left');
const divDetailsRight = document.querySelector('#details .right');
const collection_id = document.querySelector('#show_collection').dataset.index;

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
    fetch("fetch_collection.php?id="+collection_id).then(fetchResponse).then(fetchCollectionJson);
}

function fetchResponse(response) {
    if (!response.ok) {return null};
    return response.json();
}

function fetchCollectionJson(json){
    console.log(json);

    const num_like = json[0].num_like;
    const category = json[0].category;
    const author = json[0].content.author;
    const credit = json[0].content.credit;
    const description = json[0].content.description;
    const dimension = json[0].content.dimension;
    const image = json[0].content.image;
    const location = json[0].content.location;
    const name = json[0].content.name;
    const period  = json[0].content.period;

    const t1 = document.createElement("span");
    t1.classList.add("little_title");
    t1.textContent = "Number of likes";

    const t2 = document.createElement("span");
    t2.classList.add("little_title");
    t2.textContent = "Department";

    const t3 = document.createElement("span");
    t3.classList.add("little_title");
    t3.textContent = "Credit";

    const t4 = document.createElement("span");
    t4.classList.add("title");
    t4.textContent = "Description";

    const t5 = document.createElement("span");
    t5.classList.add("little_title");
    t5.textContent = "Dimension";

    const t6 = document.createElement("span");
    t6.classList.add("now_on_view");
    t6.textContent = "Now on view";

    const s1 = document.createElement("span");
    s1.textContent = num_like;
    const s2 = document.createElement("span");
    s2.textContent = category;
    const s3 = document.createElement("span");
    s3.textContent = author;
    s3.classList.add('title');
    const s4 = document.createElement("span");
    s4.textContent = credit;
    const s5 = document.createElement("span");
    s5.textContent = description;
    const s6 = document.createElement("span");
    s6.textContent = dimension;
    const s7 = document.createElement("img");
    s7.src = image;
    const s8 = document.createElement("span");
    s8.textContent = location;
    const s9 = document.createElement("span");
    s9.textContent = name;
    const s10 = document.createElement("span");
    s10.textContent = period;

    divImage.appendChild(s7);
    divDetailsLeft.appendChild(s3);
    divDetailsLeft.appendChild(s9);
    divDetailsLeft.appendChild(s10);
    divDetailsLeft.appendChild(t6);
    divDetailsLeft.appendChild(s8);

    divDetailsRight.appendChild(t4);
    divDetailsRight.appendChild(s5);
    divDetailsRight.appendChild(t3);
    divDetailsRight.appendChild(s4);
    divDetailsRight.appendChild(t5);
    divDetailsRight.appendChild(s6);
    divDetailsRight.appendChild(t2);
    divDetailsRight.appendChild(s2);
    divDetailsRight.appendChild(t1);
    divDetailsRight.appendChild(s1);

}