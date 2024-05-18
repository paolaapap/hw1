const imgShowPss = document.querySelector('#show_pss');
const formChangePassword = document.forms['form_change_password'];

function showPss(){
    formChangePassword.old_password.type = 'input';
    formChangePassword.new_password.type = 'input';
    formChangePassword.new_password_confirm.type = 'input';
}

imgShowPss.addEventListener('mousedown', showPss);

function hidePss(){
    formChangePassword.old_password.type = 'password';
    formChangePassword.new_password.type = 'password';
    formChangePassword.new_password_confirm.type = 'password';
}

imgShowPss.addEventListener('mouseup', hidePss);

//GENERICA FUNZIONE PER STAMPARE SCRITTE SOTTO LE CASELLE DI INPUT 
function printSentence(inputElement, message, class_){
    if (inputElement.nextElementSibling.classList.contains('error') || inputElement.nextElementSibling.classList.contains('right')) {
        inputElement.nextElementSibling.remove();
    }
    const div = document.createElement("div");
    div.textContent = message;
    div.classList.add(class_);
    inputElement.insertAdjacentElement('afterend', div);
}

/// VALIDAZIONE NUOVA PASSWORD
const minLength = /.{8,}/;
const hasUppercase = /[A-Z]/;
const hasLowercase = /[a-z]/;
const hasNumber = /[0-9]/;
const hasSpecialChar = /[!@#$%^&*(),.?]/;

function validazione_nuova_password() {
    const result =  minLength.test(formChangePassword.new_password.value) &&
                    hasUppercase.test(formChangePassword.new_password.value) &&
                    hasLowercase.test(formChangePassword.new_password.value) &&
                    hasNumber.test(formChangePassword.new_password.value) &&
                    hasSpecialChar.test(formChangePassword.new_password.value);
    
    if (result) {
        printSentence(formChangePassword.new_password, "This password is valid", "right");
    } else {
        printSentence(formChangePassword.new_password, 
            "Password must be at least 8 characters, 1 uppercase, 1 lowercase, 1 number and 1 special character [!@#$%^&*(),.?]", "error");
    }
    return result;
}

formChangePassword.new_password.addEventListener("input", validazione_nuova_password);


function matchPassword(){
    if(formChangePassword.new_password.value == formChangePassword.new_password_confirm.value){
        printSentence(formChangePassword.new_password_confirm, "Passwords match", "right"); 
        return true; 
    }
    else{
        printSentence(formChangePassword.new_password_confirm, "Passwords don't match", "error");   
        return false; 
    }
}

formChangePassword.new_password_confirm.addEventListener("input", matchPassword);

function differentPassword(){
    if(formChangePassword.old_password.value == formChangePassword.new_password.value){
        printSentence(formChangePassword.new_password, "Your new password must be different from your old", "error"); 
        return false;
    }
    else{
        return true;
    }
}

formChangePassword.new_password.addEventListener("input", differentPassword);

function checkPassordBeforSubmit(event){
    if(!validazione_nuova_password() || !matchPassword() || !differentPassword())
        event.preventDefault();
}

formChangePassword.addEventListener("submit", checkPassordBeforSubmit);


//GENERICA FUNZIONE PER STAMPARE GLI ERRORI SOTTO LE CASELLE DI INPUT E FARE IL CHECK DELLA LUNGHEZZA DELL'INPUT
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
    //non so perche se chiamo le funzioni dentro l'if partono solo per email
    (checkInput(formChangePassword.email, "Enter your email"));
    (checkInput(formChangePassword.old_password, "Enter your old password"));
    (checkInput(formChangePassword.new_password, "Enter a new passowrd"));
    (checkInput(formChangePassword.new_password_confirm, "Repeat password"));

    if((checkInput(formChangePassword.email, "Enter your email")) || (checkInput(formChangePassword.old_password, "Enter your old password")) || 
        (checkInput(formChangePassword.new_password, "Enter a new passowrd")) || (checkInput(formChangePassword.new_password_confirm, "Repeat password"))){

        event.preventDefault();
    }  
}

formChangePassword.addEventListener('submit', validazione_input);

