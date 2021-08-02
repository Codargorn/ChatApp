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
const template = `
    <a href="#"
       class="list-group-item list-group-item-action list-group-item-light rounded-0 border-top"
       data-user-id="">
        <div class="d-flex align-items-center justify-content-between mb-1">
            <h6 class="mb-0 name"></h6>
        </div>
    </a>`;

/**
 * @param {HTMLElement} $element
 * @param {User} user
 */
function mount($element, user) {
    const $user = createElementFromHTML(template);

    $user.querySelector('.name').innerHTML = user.name;
    $user.setAttribute('data-user-id', user.id);
    $user.addEventListener('click', _=> {
        document.dispatchEvent(new CustomEvent('user-selected', {
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