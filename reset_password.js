const imgShowPss = document.querySelector('#show_pss');
const formResetPassword = document.forms['form_reset_password'];

function showPss(){
    formResetPassword.password.type = 'input';
    formResetPassword.password_confirm.type = 'input';
}

imgShowPss.addEventListener('mousedown', showPss);

function hidePss(){
    formResetPassword.password.type = 'password';
    formResetPassword.password_confirm.type = 'password';
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

/// VALIDAZIONE TOKEN
const regexNum = /^[0-9]+$/;
const regexLeng = /^.{5}$/;


function validazione_token() {
    const result = regexNum.test(formResetPassword.token.value) && regexLeng.test(formResetPassword.token.value);
    if (!(result)) {
        printSentence(formResetPassword.token, "Token is not valid", "error");
    } else {
        if (formResetPassword.token.nextElementSibling.classList.contains('error')) {
            formResetPassword.token.nextElementSibling.remove();
        } 
    }
    return result;
}

formResetPassword.token.addEventListener("input", validazione_token);


/// VALIDAZIONE PASSWORD
const minLength = /.{8,}/;
const hasUppercase = /[A-Z]/;
const hasLowercase = /[a-z]/;
const hasNumber = /[0-9]/;
const hasSpecialChar = /[!@#$%^&*(),.?]/;

function validazione_password() {
    const result =  minLength.test(formResetPassword.password.value) &&
                    hasUppercase.test(formResetPassword.password.value) &&
                    hasLowercase.test(formResetPassword.password.value) &&
                    hasNumber.test(formResetPassword.password.value) &&
                    hasSpecialChar.test(formResetPassword.password.value);
    
    if (result) {
        printSentence(formResetPassword.password, "This password is valid", "right");
    } else {
        printSentence(formResetPassword.password, 
            "Password must be at least 8 characters, 1 uppercase, 1 lowercase, 1 number and 1 special character [!@#$%^&*(),.?]", "error");
    }
    return result;
}

formResetPassword.password.addEventListener("input", validazione_password);


function matchPassword(){
    if(formResetPassword.password.value == formResetPassword.password_confirm.value){
        printSentence(formResetPassword.password_confirm, "Passwords match", "right"); 
        return true; 
    }
    else{
        printSentence(formResetPassword.password_confirm, "Passwords don't match", "error");   
        return false; 
    }
}

formResetPassword.password_confirm.addEventListener("input", matchPassword);

function checkPassordBeforSubmit(event){
    if(!validazione_password() || !matchPassword() || !validazione_token())
        event.preventDefault();
}

formResetPassword.addEventListener("submit", checkPassordBeforSubmit);

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
    (checkInput(formResetPassword.email, "Enter your email"));
    (checkInput(formResetPassword.token, "Enter token"));
    (checkInput(formResetPassword.password, "Eneter your new password"));
    (checkInput(formResetPassword.password_confirm, "Repeat password"));

    if((checkInput(formResetPassword.email, "Enter your email")) || (checkInput(formResetPassword.token, "Enter token")) || 
    (checkInput(formResetPassword.password, "Eneter your new password")) || (checkInput(formResetPassword.password_confirm, "Repeat password"))){

        event.preventDefault();
    }  
}

formResetPassword.addEventListener('submit', validazione_input);
