import UserListComponent from "./app/components/userlist.js";
import MessageComponent from "./app/components/message.js";
import MessageListComponent from "./app/components/messagelist.js";
import FrameComponent from "./app/components/frame.js";
import LoginComponent from "./app/components/login.js";
import LogoutComponent from "./app/components/logout.js";

export default function App($app) {

    let eventSourcedMessages;

    const store = window.localStorage;
    if (!store) {
        throw new Error('localStorage not available')
    }

    document.addEventListener('user-logged-in', e => {
        fetch(`/api/users.php?logged_in_user_id=${store.getItem('currentUserId')}`).then( response => {
            if (response.status === 200) {
                return  response.text();
            }
            throw new Error('could not fetch data');
        })
            .then(json => {
                const userList = new UserListComponent.UserList()
                UserListComponent.mount(document.querySelector('.contacts-box'),userList.fromJSON(json))
            }).catch(error => {});
    });

    document.dispatchEvent(new CustomEvent('user-logged-in'));



    document.addEventListener('user-selected', e => {
        fetch(`/api/messages.php?sender_id=${store.getItem('currentUserId')}&receiver_id=${e.detail}`).then( response => {
            if (response.status === 200) {
                return  response.text();
            }
            throw new Error('could not fetch data');
        })
            .then(json => {
                const messageList = new MessageListComponent.MessageList()
                MessageListComponent.mount(document.querySelector('.chat-box'),messageList.fromJSON(json))
                document.querySelector('.chat-box').scrollTop = 999999;
            });
    });

    document.addEventListener('start-stream',e =>{

        if ( eventSourcedMessages instanceof EventSource)
        {
            eventSourcedMessages.close();
        }

        eventSourcedMessages = new EventSource(`/api/messages_event_source.php?sender_id=${store.getItem('currentUserId')}&receiver_id=${e.detail}`)

        eventSourcedMessages.onmessage = function(event) {
            const serializedMessage = JSON.parse(event.data)

            const lastMessage = document.querySelector(`div[data-message-id="${event.lastEventId}"]`);

            if ( lastMessage)
            {
                return;
            }

            let message = new MessageComponent.Message(
                serializedMessage.id,
                serializedMessage.text,
                serializedMessage.sender_id,
                serializedMessage.receiver_id,
                new Date(serializedMessage.createdAt)
            )
            MessageComponent.mount(document.querySelector('.chat-box'),message)
            document.querySelector('.chat-box').scrollTop = 999999;
        }
    });





    FrameComponent.mount(document.querySelector('.chat-app'))
    LoginComponent.mount(document.querySelector('.app'));
    LogoutComponent.mount(document.querySelector('.app'));

}

