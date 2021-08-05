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


    $emailInput.addEventListener('input', _ => {
        if (isEmailValid($emailInput)) {
            console.log($emailInput.value)
            checkEmailNew($emailInput)
                .then(body => {
                    console.log(body.success)
                    if (body.success) {

                        $emailValidate.innerHTML = 'gute Mail'

                    } else {
                        $emailValidate.innerHTML = 'Email already exists'
                    }
                });


        } else {
            console.log($emailInput.value)
            $emailValidate.innerHTML = 'email is not valid'
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

        checkEmailNew($emailInput).then(body => {
            if (body.success === false) {
                throw new Error('email exists')
            }
        }).then(_ => {
            if (!isPasswordValid($password)) {
                throw new Error('password not valid')
            }
        }).then(_ => {
            if (!doesPasswordsMatch($password, $passwordRepeat)) {
                throw new Error('passwords do not match')
            }
        }).then(_ => {
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
        }).catch(error => {
            console.error(error);
        });
    })


    $element.appendChild($registration);
}


function doesPasswordsMatch($password1, $password2) {
    return $password1.value === $password2.value
}

function isPasswordValid($passwordInput) {
    return $passwordInput.value.length >= 9;


}

function isEmailValid($email) {

    return $email.checkValidity()
}


function checkEmailNew($emailInput, callable) {

    const emailForm = new FormData();
    emailForm.append('email', $emailInput.value);
    return fetch('/api/email.php', {method: 'POST', body: emailForm})
        .then(response => response.json());
}


const RegistrationComponent = {
    mount
}

export default RegistrationComponent