import Store from "../store.js";

/**
 *
 * @param  {HTMLElement} $app
 */
function mount($app) {
    const store = new Store()

    const $logout = $app.querySelector('.signout');

    $logout.addEventListener('click', _ => {
        fetch('/api/logout.php', {method: 'GET'})
            .then(response => response.json())
            .then(body => {
                if (body.success) {
                    const $login = $app.querySelector('.login');
                    $login.querySelector('#email').value = '';
                    $login.querySelector('#password').value = '';
                    $login.style.display = 'block';

                    $app.querySelector('.content').style.display = 'none';
                    document.querySelector('.contacts-box').innerHTML = "";
                    store.clear();

                }
            });
    })
    $app.querySelector('.settings').addEventListener('click', _=>{
        window.location = "./settings.html"
    })
}

const LogoutComponent = {
    mount
}
export default LogoutComponent