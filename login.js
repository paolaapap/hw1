const imgShowPss = document.querySelector('#show_pss');
const loginForm = document.forms['form_login'];
const emailError = document.querySelector('#email_error');
const passwordError = document.querySelector('#password_error');

function showPss(){
    loginForm.password.type = 'input';
}

imgShowPss.addEventListener('mousedown', showPss);

function hidePss(){
    loginForm.password.type = 'password';
}

imgShowPss.addEventListener('mouseup', hidePss);

function validazione(event){
    if(loginForm.email.value.length == 0 || loginForm.password.value.length == 0){
        if(loginForm.email.value.length == 0){
            emailError.classList.remove('hidden');
            loginForm.email.classList.add('error_input');
        }
        if(loginForm.password.value.length == 0){
            passwordError.classList.remove('hidden');
            loginForm.password.classList.add('error_input');
        }

        // Blocca l'invio del form
        event.preventDefault();
    }
        
}

loginForm.addEventListener('submit', validazione);