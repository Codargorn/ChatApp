import {createElementFromHTML} from "../html.js";

class Message {
    constructor(id, text, senderId, receiverId,createdAt = new Date()) {
        this.id = id;
        this.text = text;
        this.senderId = senderId;
        this.receiverId = receiverId;
        this.createdAt = createdAt;

        this.messageList = null;
    }

    addTo(messageList) {
        messageList.add(this);
    }

}
const receiverTemplate =  `
    <div class="w-50 mb-3 ml-3">
            <div class="bg-light rounded py-2 px-3 mb-2">
              <p class=" message-text text-small mb-0 text-muted"></p>
            </div>
            <p class=" time small text-muted">12:00 PM | Aug 13</p>
      </div>`;

const senderTemplate =  `
        <div class="w-50 ml-auto mb-3">
            <div class="bg-primary rounded py-2 px-3 mb-2">
                <p class=" message-text text-small mb-0 text-white"></p>
            </div>
            <p class=" time small text-muted"></p>
        </div>
        `;
/**
 * @param {HTMLElement} $element
 * @param {Message} message
 */
function mount($element, message){
    const store = window.localStorage;
    if (!store) {
        throw new Error('localStorage not available')
    }
let $message = "";
if (message.senderId != store.getItem('currentUserId'))
{
    $message = createElementFromHTML(receiverTemplate);

}
else
{
    $message = createElementFromHTML(senderTemplate);
}
    $message.setAttribute('data-message-id', message.id);
    $message.querySelector('.message-text').innerHTML = message.text;
    $message.querySelector('.time').innerHTML = createTimeString(message);


    $element.appendChild($message);
}

function createTimeString(message){

    const navigatorAgent = window.navigator.userAgent;
    if (!navigatorAgent) {
        throw new Error('User Agent not available')
    }

    const is_chrome = navigatorAgent.indexOf('Chrome') > -1;
    const is_safari = navigatorAgent.indexOf("Safari") > -1;

    if (!is_chrome && is_safari){
        return "Use another browser to see the time ffs"
    }

    const dt = message.createdAt;
    let hours = dt.getHours() ;
    const AmOrPm = hours >= 12 ? 'PM' : 'AM';
    hours = (hours % 12) || 12;
    const minutes = dt.getMinutes() ;
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    const month = monthNames[dt.getMonth()]
    const day = dt.getDate();
    return `${hours}:${minutes} ${AmOrPm} | ${month} ${day}`

}


const MessageComponent= {
    Message,
    mount
}

export default MessageComponent;