const imgShowPss = document.querySelector('#show_pss');
const formResetPassword = document.forms['form_reset_password'];
const emailError = document.querySelector('#email_error');
const tokenError = document.querySelector('#token_error');
const tokenNotValid = document.querySelector('#token_notvalid');
const passwordError = document.querySelector('#password_error');
const passwordConfirmError = document.querySelector('#password_confirm_error');
const passwordAccepted = document.querySelector('#password_accepted');
const passwordRequirements = document.querySelector('#password_requirements');
const passwordMatch = document.querySelector('#password_match');
const passwordDontMatch = document.querySelector('#password_dont_match');
const allErrors = document.querySelectorAll('.error');

function hideAllErrors() {
    for (a of allErrors) {
      a.classList.add('hidden');
    }
}

window.onload = hideAllErrors;

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

function validazione_input(event){
    if(formResetPassword.email.value.length == 0 || formResetPassword.token.value.length == 0 ||  formResetPassword.password.value.length == 0 || 
        formResetPassword.password_confirm.value.length == 0){

        if(formResetPassword.email.value.length == 0){
            emailError.classList.remove('hidden');
            formResetPassword.email.classList.add('error_input');
        }

        if(formResetPassword.token.value.length == 0){
            tokenError.classList.remove('hidden');
            formResetPassword.token.classList.add('error_input');
        }

        if(formResetPassword.password.value.length == 0){
            passwordError.classList.remove('hidden');
            formResetPassword.password.classList.add('error_input');
        }

        if(formResetPassword.password_confirm.value.length == 0){
            passwordConfirmError.classList.remove('hidden');
            formResetPassword.password_confirm.classList.add('error_input');
        }

        // Blocca l'invio del form
        event.preventDefault();
    }
        
}

formResetPassword.addEventListener('submit', validazione_input);

/// VALIDAZIONE TOKEN
const regexNum = /^[0-9]+$/;
const regexLeng = /^.{5}$/;


function validazione_token() {
    const result = regexNum.test(formResetPassword.token.value) && regexLeng.test(formResetPassword.token.value);
    if (result) {
        tokenNotValid.classList.add('hidden');
    } else {
        tokenNotValid.classList.remove('hidden');
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
        passwordRequirements.classList.add('hidden');
        passwordAccepted.classList.remove('hidden');
    } else {
        passwordRequirements.classList.remove('hidden');
        passwordAccepted.classList.add('hidden');
    }

    return result;
}

formResetPassword.password.addEventListener("input", validazione_password);


function matchPassword(){
    if(formResetPassword.password.value == formResetPassword.password_confirm.value){
        passwordMatch.classList.remove('hidden');
        passwordDontMatch.classList.add('hidden');   
        return true; 
    }
    else{
        passwordMatch.classList.add('hidden');
        passwordDontMatch.classList.remove('hidden');   
        return false; 
    }
}

formResetPassword.password_confirm.addEventListener("input", matchPassword);

function checkPassordBeforSubmit(event){
    if(!validazione_password() || !matchPassword() || !validazione_token())
        event.preventDefault();
}

formResetPassword.addEventListener("submit", checkPassordBeforSubmit);