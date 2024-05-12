const formForgotPassword = document.forms['form_forgot_password'];
const emailError = document.querySelector('#email_error');
const allErrors = document.querySelectorAll('.error');

function hideAllErrors() {
    for (a of allErrors) {
      a.classList.add('hidden');
    }
}

window.onload = hideAllErrors;

function validazione_input(event){
    
    if(formForgotPassword.email.value.length == 0){
        emailError.classList.remove('hidden');
        formForgotPassword.email.classList.add('error_input');
        event.preventDefault();
    }
}
        

formForgotPassword.addEventListener('submit', validazione_input);

