const imgShowPss = document.querySelector('#show_pss');
const loginForm = document.forms['form_login'];


function showPss(){
    loginForm.password.type = 'input';
}

imgShowPss.addEventListener('mousedown', showPss);

function hidePss(){
    loginForm.password.type = 'password';
}

imgShowPss.addEventListener('mouseup', hidePss);


function checkInput(inputElement, errorMessage){
    if (inputElement.value.length == 0) {
        if (inputElement.nextElementSibling.classList.contains('error') || inputElement.nextElementSibling.classList.contains('right')) {
            inputElement.nextElementSibling.remove();
        }
        const error = document.createElement("div");
        error.textContent = errorMessage;
        error.classList.add("error");
        inputElement.insertAdjacentElement('afterend', error);
        inputElement.classList.add('error_input');
        return true;
    } else {
        return false;
    }
}

function validazione_input(event){
    checkInput(loginForm.email, "Enter your email");
    checkInput(loginForm.password, "Enter your password");
    if(checkInput(loginForm.email, "Enter your email") || checkInput(loginForm.password, "Enter your password")){
        event.preventDefault();
    }  
}

loginForm.addEventListener('submit', validazione_input);

