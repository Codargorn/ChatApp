import {createElementFromHTML} from "../html.js";

class Message {
    constructor(text, senderId, receiverId,createdAt = new Date()) {
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
const template =  `
    <div class="w-50 mb-3 ml-3">
            <div class="bg-light rounded py-2 px-3 mb-2">
              <p class=" message-text text-small mb-0 text-muted"></p>
            </div>
            <p class=" time small text-muted">12:00 PM | Aug 13</p>
      </div>`;

/**
 * @param {HTMLElement} $element
 * @param {Message} message
 */
function mount($element, message){


    const $message = createElementFromHTML(template);
    $message.querySelector('.message-text').innerHTML = message.text;
    $message.querySelector('.time').innerHTML = createTimeString(message);

    console.log(message.createdAt)
    $element.appendChild($message);
}

function createTimeString(message){
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