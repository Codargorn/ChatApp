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
    this.fromJSON = function (json) {
        messages = [];

        const list = JSON.parse(json);
        for (let i = 0; i < list.length; i++) {
            const serializedMessage = list[i];
            const message = new MessageComponent.Message(
                serializedMessage.text,
                serializedMessage.sender_id,
                serializedMessage.receiver_id,
                new Date(serializedMessage.createdAt),

            );
            message.messageList = this;

            messages.push(message);
        }

        return this;
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