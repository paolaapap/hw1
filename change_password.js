const imgShowPss = document.querySelector('#show_pss');
const formChangePassword = document.forms['form_change_password'];
const emailError = document.querySelector('#email_error');
const oldPasswordError = document.querySelector('#old_password_error');
const newPasswordError = document.querySelector('#new_password_error');
const newPasswordConfirmError = document.querySelector('#new_password_confirm_error');
const newPasswordAccepted = document.querySelector('#new_password_accepted');
const newPasswordRequirements = document.querySelector('#new_password_requirements');
const passwordMatch = document.querySelector('#password_match');
const passwordDontMatch = document.querySelector('#password_dont_match');
const samePasswordError = document.querySelector('#same_passwords');
const allErrors = document.querySelectorAll('.error');

function hideAllErrors() {
    for (a of allErrors) {
      a.classList.add('hidden');
    }
}

window.onload = hideAllErrors;

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

function validazione_input(event){
    if(formChangePassword.email.value.length == 0 || formChangePassword.old_password.value.length == 0 || 
        formChangePassword.new_password.value.length == 0 || formChangePassword.new_password_confirm.value.length == 0){

        if(formChangePassword.email.value.length == 0){
            emailError.classList.remove('hidden');
            formChangePassword.email.classList.add('error_input');
        }

        if(formChangePassword.old_password.value.length == 0){
            oldPasswordError.classList.remove('hidden');
            formChangePassword.old_password.classList.add('error_input');
        }

        if(formChangePassword.new_password.value.length == 0){
            newPasswordError.classList.remove('hidden');
            formChangePassword.new_password.classList.add('error_input');
        }

        if(formChangePassword.new_password_confirm.value.length == 0){
            newPasswordConfirmError.classList.remove('hidden');
            formChangePassword.new_password_confirm.classList.add('error_input');
        }

        // Blocca l'invio del form
        event.preventDefault();
    }
        
}

formChangePassword.addEventListener('submit', validazione_input);

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
        newPasswordRequirements.classList.add('hidden');
        newPasswordAccepted.classList.remove('hidden');
    } else {
        newPasswordRequirements.classList.remove('hidden');
        newPasswordAccepted.classList.add('hidden');
    }

    return result;
}

formChangePassword.new_password.addEventListener("input", validazione_nuova_password);


function matchPassword(){
    if(formChangePassword.new_password.value == formChangePassword.new_password_confirm.value){
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

formChangePassword.new_password_confirm.addEventListener("input", matchPassword);

function differentPassword(){
    if(formChangePassword.old_password.value == formChangePassword.new_password.value){
        samePasswordError.classList.remove('hidden');
        newPasswordAccepted.classList.add('hidden');
        return false;
    }
    else{
        samePasswordError.classList.add('hidden');
        return true;
    }
}

formChangePassword.new_password.addEventListener("input", differentPassword);

function checkPassordBeforSubmit(event){
    if(!validazione_nuova_password() || !matchPassword() || !differentPassword())
        event.preventDefault();
}

formChangePassword.addEventListener("submit", checkPassordBeforSubmit);