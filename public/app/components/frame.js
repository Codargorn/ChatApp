import {createElementFromHTML} from "../html.js";

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
          <input type="text" placeholder="Type a message" aria-describedby="button-addon2" class="form-control rounded-0 border-0 py-4 bg-light">
          <div class="input-group-append">
            <button id="button-addon2" class="btn btn-link"> <i class="fas fa-paper-plane"></i></button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
        `;

function mount($element){
    const $frame = createElementFromHTML(template);

    $element.appendChild($frame);

    document.dispatchEvent(new CustomEvent('app-mounted', {
        detail: {app: $element}
    }));
}




const FrameComponent = {
    mount
}

export default FrameComponent