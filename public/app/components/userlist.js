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
    this.fromJSON = function (json) {
        users = [];

        const list = JSON.parse(json);
        for (let i = 0; i < list.length; i++) {
            const serializedUser = list[i];
            const user = new UserComponent.User(
                serializedUser.id,
                serializedUser.username
            );
            user.userList = this;

            users.push(user);
        }


        return this;
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