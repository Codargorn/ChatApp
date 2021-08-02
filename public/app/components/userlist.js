import UserComponent from "./user.js";
/**
 *
 * @param {User[]} users
 * @constructor
 */
function UserList(users = []) {

    for (let i = 0; i < users.length; i++) {
        users[i].userList = this;
    }

    /**
     * @param {User} user
     */
    this.add = function (user) {
        user.userList = this;
        users.push(user);
    }

    this.users = function () {
        return users;
    }
}

/**
 * @param {HTMLElement} $element
 * @param {UserList} userList
 */
function mount($element,userList){

    for (let i in userList.users()) {
        /**
         * @type {User}
         */
        const user = userList.users()[i];

        UserComponent.mount($element, user)
    }
}

const UserListComponent = {
    UserList,
    mount
}

export default UserListComponent;