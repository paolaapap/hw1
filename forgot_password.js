const formForgotPassword = document.forms['form_forgot_password'];

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
    if(checkInput(formForgotPassword.email, "Enter your email")){
        event.preventDefault();
    }  
}

formForgotPassword.addEventListener('submit', validazione_input);


