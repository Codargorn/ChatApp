import {createElementFromHTML} from "../html.js";
import Store from "../store.js";

const template = `
<div class="py-5 px-4">

  <div class="row rounded-lg overflow-hidden shadow">
    <!-- Users box-->
    <div class="col-5 px-0 bg-white">

        <div class="bg-gray px-4 py-2 bg-light">
          <p class="h5 mb-0 py-1">Contacts</p>
        </div>

        <div class="contacts-box list-group rounded-0">


       </div>
    </div>
    <!-- Chat Box-->
    <div class="col-7 px-0">
      <div class="px-4 py-5 chat-box bg-white">
   
       
      </div>

      <!-- Typing area -->
      <form action="#" class="bg-light">
        <div class="input-group">
          <input type="text" placeholder="Type a message" aria-describedby="button-submit" class=" text-input form-control rounded-0 border-0 py-4 bg-light">
          <div class="input-group-append">
            <button id="button-submit" class="btn"> <i class="fas fa-paper-plane"></i></button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
        `;

function mount($element){
    const $frame = createElementFromHTML(template);
    let receiverId = ""

  const store = new Store()

    document.addEventListener('user-selected',e =>{
            receiverId = e.detail;

    })

    $frame.querySelector('#button-submit').addEventListener('click', _=>{
        const submitForm = new FormData();
        let $textInput = $frame.querySelector('.text-input')
        submitForm.append('text', $textInput.value)
        submitForm.append('sender_id', store.getItem('currentUserId'))
        submitForm.append('receiver_id',receiverId)

        if($textInput.value.length !== 0){
        fetch('/api/message.php', {method: 'POST', body: submitForm})
            .then(response => response.json())
            .then( body => {
                if(body.success){
                    $textInput.value = "";
                }
            })
    }});


    $element.appendChild($frame);

    document.dispatchEvent(new CustomEvent('app-mounted', {
        detail: {app: $element}
    }));
}




const FrameComponent = {
    mount
}

export default FrameComponent