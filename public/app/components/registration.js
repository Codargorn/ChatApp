import {createElementFromHTML} from "../html.js";

const template = ` 
 <div class="container">
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control" id="email">
      <label id="emailvalidate"></label>
    </div>
     <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" id="username">
      <label id="usernamevalidate"></label>
    </div>
    
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" autoComplete="off">
      <label id ="passwordvalidate"></label>
    </div>
    <div class="mb-3">
      <label for="passwordrepeat" class="form-label">Repeat your Password</label>
      <input type="password" class="form-control" id="passwordrepeat" autoComplete="off">
      <label id="passwordrepeatvalidate"></label>
    </div>
    <button class="btn btn-primary signin">Create Account</button>
  </div>`

/**
 *
 * @param {HTMLElement} $element
 */
function mount($element) {

    const $registration = createElementFromHTML(template);

    const $emailInput = $registration.querySelector('#email');
    const $emailValidate = $registration.querySelector('#emailvalidate');
    const $username = $registration.querySelector('#username');
    const $usernameValidate = $registration.querySelector('#usernameValidate')
    const $password = $registration.querySelector('#password');
    const $passwordValidate = $registration.querySelector('#passwordvalidate');
    const $passwordRepeat = $registration.querySelector('#passwordrepeat');
    const $passwordRepeatValidate = $registration.querySelector('#passwordrepeatvalidate');
    const $signin = $registration.querySelector('.signin');


    $emailInput.addEventListener('keypress', _ => {
        if (!isEmailValid($emailInput)) {
            console.log('test')
            $emailValidate.innerHTML = 'email is not valid'
        } else {

            const form = new FormData();
            form.append('email', $emailInput.value);

/*            if (isEmailNew(form)) {
                $emailValidate.innerHTML = 'Email already exists'
            } else {
                $emailValidate.innerHTML = 'gute Mail'
            }*/

        }
    })
    $password.addEventListener('keyup', _ => {
        let text = isPasswordValid($password)
        if ((typeof text) === 'string') {
            $passwordValidate.innerHTML = text;
        } else {
            $passwordValidate.innerHTML = '';
        }

    })
    $passwordRepeat.addEventListener('keyup', _ => {
        if (!doesPasswordsMatch($password, $passwordRepeat)) {
            $passwordRepeatValidate.innerHTML = "Password's don't match";
        } else {
            $passwordRepeatValidate.innerHTML = ""
        }

    })

    $signin.addEventListener('click', _ => {
        const form = new FormData();
        form.append('email', $emailInput.value);
        if (!isEmailValid($emailInput)) {
            return;
        }

        /*if ( !isEmailNew(form) ){
          return;
        }

        if ( !isPasswordValid(form) ){
            return;
        }

        if ( !doesPasswordsMatch(form) ){
            return;
        }*/

        const submitForm = new FormData();
        submitForm.append('email', $emailInput.value);
        submitForm.append('password', $password.value);
        submitForm.append('username', $username.value);
        fetch('/api/registration.php', {method: 'POST', body: submitForm})
            .then(response => response.json())
            .then(body => {
                if (body.success) {
                    console.log('went well')
                    window.location = "./index.html"
                }

            })
            .catch((error) => {
                console.error('Error:', error);
            });
    })


    $element.appendChild($registration);
}


function doesPasswordsMatch($password1, $password2) {
    return $password1.value === $password2.value
}

function isPasswordValid($passwordInput) {
    if ($passwordInput.value === '') {
        return 'Password is required'
    } else if ($passwordInput.value.length < 9) {
        return 'Password must be at least 9 characters.'
    } else {
        return true
    }
}

function isEmailValid($email) {

    return $email.checkValidity()
}

// funktioniert so nicht
function isEmailNew(form) {

    fetch('/api/registration.php', {method: 'POST', body: form})
        .then(response => response.json())
        .then(body => {
            return body.success
        });
}


const RegistrationComponent = {
    mount
}

export default RegistrationComponent