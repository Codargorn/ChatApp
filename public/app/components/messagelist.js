import MessageComponent from "./message.js";

/**
 *
 * @param {Message[]} messages
 * @constructor
 */
function MessageList(messages = []) {

    for (let i = 0; i < messages.length; i++) {
        messages[i].messageList = this;
    }

    /**
     * @param {Message} message
     */
    this.add = function (message) {
        message.messageList = this;
        messages.push(message);
    }

    this.messages = function () {
        return messages;
    }
}

/**
 * @param {HTMLElement} $element
 * @param {MessageList} messageList
 */
function mount($element,messageList){

    for (let i in messageList.messages()) {
        /**
         * @type {Message}
         */
        const message = messageList.messages()[i];

        MessageComponent.mount($element, message);
    }
}

const MessageListComponent = {
    MessageList,
    mount
}

export default MessageListComponent;