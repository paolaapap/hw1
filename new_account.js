const imgShowPss = document.querySelector('#show_pss');
const formNewAccount = document.forms['form_new_account'];
const lastNameError = document.querySelector('#last_name_error');
const firstNameError = document.querySelector('#fisrt_name_error');
const emailError = document.querySelector('#email_error');
const passwordError = document.querySelector('#password_error');
const passwordConfirmError = document.querySelector('#password_confirm_error');
const passwordAccepted = document.querySelector('#password_accepted');
const passwordRequirements = document.querySelector('#password_requirements');
const passwordMatch = document.querySelector('#password_match');
const passwordDontMatch = document.querySelector('#password_dont_match');
const lastNameNotValid = document.querySelector('#last_name_notvalid');
const firstNameNotValid = document.querySelector('#first_name_notvalid');
const allErrors = document.querySelectorAll('.error');

function hideAllErrors() {
    for (a of allErrors) {
      a.classList.add('hidden');
    }
}

window.onload = hideAllErrors;

function showPss(){
    formNewAccount.password.type = 'input';
    formNewAccount.password_confirm.type = 'input';
}

imgShowPss.addEventListener('mousedown', showPss);

function hidePss(){
    formNewAccount.password.type = 'password';
    formNewAccount.password_confirm.type = 'password';
}

imgShowPss.addEventListener('mouseup', hidePss);

function validazione_input(event){
    if(formNewAccount.first_name.value.length == 0 || formNewAccount.last_name.value.length == 0 || 
        formNewAccount.email.value.length == 0 || formNewAccount.password.value.length == 0 || formNewAccount.password_confirm.value.length == 0){

        if(formNewAccount.first_name.value.length == 0){
            firstNameError.classList.remove('hidden');
            formNewAccount.first_name.classList.add('error_input');
        }

        if(formNewAccount.last_name.value.length == 0){
            lastNameError.classList.remove('hidden');
            formNewAccount.last_name.classList.add('error_input');
        }

        if(formNewAccount.email.value.length == 0){
            emailError.classList.remove('hidden');
            formNewAccount.email.classList.add('error_input');
        }

        if(formNewAccount.password.value.length == 0){
            passwordError.classList.remove('hidden');
            formNewAccount.password.classList.add('error_input');
        }

        if(formNewAccount.password_confirm.value.length == 0){
            passwordConfirmError.classList.remove('hidden');
            formNewAccount.password_confirm.classList.add('error_input');
        }

        // Blocca l'invio del form
        event.preventDefault();
    }
        
}

formNewAccount.addEventListener('submit', validazione_input);

/// VALIDAZIONE NOME E COGNOME
const nameRegex = /^[a-zA-ZàèìòùÀÈÌÒÙçÇ ]+$/; // Solo lettere e spazi

function validazione_cognome() {
    const result = nameRegex.test(formNewAccount.last_name.value);
    if (result) {
        lastNameNotValid.classList.add('hidden');
    } else {
        lastNameNotValid.classList.remove('hidden');
    }
    return result;
}

function validazione_nome() {
    const result = nameRegex.test(formNewAccount.first_name.value);
    if(result) {
        firstNameNotValid.classList.add('hidden');
    } else {
        firstNameNotValid.classList.remove('hidden');
    }
    return result;
}

formNewAccount.last_name.addEventListener("input", validazione_cognome);
formNewAccount.first_name.addEventListener("input", validazione_nome);


/// VALIDAZIONE EMAIL VIENE FATTA DI DEFAUL DAL TIPO EMAIL NELL HTML

/// VALIDAZIONE PASSWORD
const minLength = /.{8,}/;
const hasUppercase = /[A-Z]/;
const hasLowercase = /[a-z]/;
const hasNumber = /[0-9]/;
const hasSpecialChar = /[!@#$%^&*(),.?]/;

function validazione_password() {
    const result =  minLength.test(formNewAccount.password.value) &&
                    hasUppercase.test(formNewAccount.password.value) &&
                    hasLowercase.test(formNewAccount.password.value) &&
                    hasNumber.test(formNewAccount.password.value) &&
                    hasSpecialChar.test(formNewAccount.password.value);
    
    if (result) {
        passwordRequirements.classList.add('hidden');
        passwordAccepted.classList.remove('hidden');
    } else {
        passwordRequirements.classList.remove('hidden');
        passwordAccepted.classList.add('hidden');
    }

    return result;
}

formNewAccount.password.addEventListener("input", validazione_password);


function matchPassword(){
    if(formNewAccount.password.value == formNewAccount.password_confirm.value){
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

formNewAccount.password_confirm.addEventListener("input", matchPassword);

function checkPassordBeforSubmit(event){
    if(!validazione_password() || !matchPassword() || !validazione_nome() || !validazione_cognome())
        event.preventDefault();
}

formNewAccount.addEventListener("submit", checkPassordBeforSubmit);
