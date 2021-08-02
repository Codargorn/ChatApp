// language=HTML
import {createElementFromHTML} from "../html.js";

const template = `
    <div class="login">
        <div class="mb-3">
            <label for="email" class=" login-label form-label ">Email address</label>
            <input type="email" class="login-input form-control" id="email">
        </div>
        <div class="mb-3">
            <label for="password" class=" login-label form-label ">Password</label>
            <input type="password" class=" login-input form-control" id="password" autocomplete="off">
        </div>
        <button class="btn btn-primary signin">Submit</button>
        <a href="../../registration.html" class="text-light float-right">not registered?</a>
    </div>
`;


/**
 * @param {HTMLElement} $app
 */
function mount($app) {
    const $login = createElementFromHTML(template);
    const $signinButton = $login.querySelector('.signin');

    $login.querySelector(`#password`).addEventListener('keyup', e => {
        if (e.code === 'Enter') {
            $signinButton.click();
        }
    })


    $signinButton.addEventListener('click', _ => {
        const form = new FormData();
        form.append('email', $login.querySelector('#email').value);
        form.append('password', $login.querySelector('#password').value)

        fetch('/api/login.php', {method: 'POST', body: form})
            .then(response => response.json())
            .then(body => {
                if (body.success) {
                    $login.style.display = 'none';
                    $app.querySelector('.content').style.display = 'block';
                    document.dispatchEvent(new CustomEvent('user-logged-in', {
                        detail: body.user_id
                    }));

                   }
            });
    })

    $app.appendChild($login);


    fetch('/api/login.php')
        .then(response => response.json())
        .then(body => {
            if (body.success) {
                $login.style.display = 'none';
                $app.querySelector('.content').style.display = 'block';

            }
        });
}

const LoginComponent = {
    mount
}

export default LoginComponent

