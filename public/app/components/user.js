import {createElementFromHTML} from "../html.js";

class User {
    constructor(id, name) {
        this.id = id;
        this.name = name;
        this.userList = null;
    }

    addTo(userList) {
        userList.add(this);
    }
}



// language=HTML
const template = (user) => `
    <a href="#"
       class="list-group-item list-group-item-action list-group-item-light rounded-0 border-top"
       data-user-id="">
        <div class="media">
        <img src="/api/image.php?user_id=${user.id}" width="50" height="50" class="rounded-circle">
            <div class="media-body ml-4">
        <div class="d-flex align-items-center justify-content-between mb-1">
             <h6 class="mb-0 name"></h6>
        </div>
        </div>
        </div>
    </a>`;

/**
 * @param {HTMLElement} $element
 * @param {User} user
 */
function mount($element, user) {
    const $user = createElementFromHTML(template(user));


    $user.querySelector('.name').innerHTML = user.name;
    $user.setAttribute('data-user-id', user.id);
    $user.addEventListener('click', _=> {

        document.querySelector('.chat-box').innerHTML = "";
        const itemList = document.querySelectorAll('.list-group-item')

        itemList.forEach($listItem =>{
            $listItem.classList.remove('active')
            $listItem.classList.remove('disabled')
        })


        $user.classList.add('active')
        $user.classList.add('disabled')
        document.dispatchEvent(new CustomEvent('user-selected', {
            detail: $user.getAttribute('data-user-id')
        }))

        document.dispatchEvent(new CustomEvent('start-stream', {
            detail: $user.getAttribute('data-user-id')
        }))
    });
    $element.appendChild($user);
}

const UserComponent = {
    User,
    mount
}

export default UserComponent;