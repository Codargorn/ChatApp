import {createElementFromHTML} from "../html.js";
import Store from "../store.js";

const template = `
<div class="py-5 px-4">

  <div class="row rounded-lg overflow-hidden shadow">
    <!-- Users box-->
    <div class="col-5 px-0 bg-white">

        <div class="bg-gray px-4 py-2 bg-light">
          <p class="h5 mb-0 py-1">Settings</p>
        </div>

        <div class="category-box list-group rounded-0">
        <a href="#" class=" image-setting list-group-item list-group-item-action list-group-item-light rounded-0 border-top"
       data-category-id="1">
        <div class="d-flex align-items-center justify-content-between mb-1">
            <h6 class="mb-0 name">Profile Picture</h6>
        </div>
    </a>

       </div>
    </div>
    <!-- Chat Box-->
    <div class="col-7 px-0 bg-white">
      <div class="px-4 py-5 settings-box bg-white">
        
      </div>
            <button id="exit" class="btn" style= "bottom: 5px; right: 20px; position: absolute">Exit</button>
    </div>
  </div>
</div>
        `;
const imageSettingTemplate = properties =>
    `
<div>
    <div class="row mb-3">
         <div class="col">
             <input class="image-upload" type="file" accept="image/jpg">
         </div>
    </div>
    <div class="row mb-3"> 
        <div class="col">
            <img class="image-preview" src="/api/image.php?user_id=${properties.id}" alt="did not work">
        </div>
    </div>
        <div class="row">
         <div class="col">
             <button id="save" class="btn btn-dark">save</button>
         </div>
    </div>
</div>
        `;


function mount($element) {
    const store = new Store();
    const $settings = createElementFromHTML(template);
    const $imageSettingTemplateElement = createElementFromHTML(imageSettingTemplate({
        id :store.getItem('currentUserId')
    }));


    const $imageSetting = $settings.querySelector('.image-setting');
    const $settingsBox = $settings.querySelector('.settings-box');
    const $imageUpload = $imageSettingTemplateElement.querySelector('.image-upload')

    $imageSetting.addEventListener('click', _ => {
        $settingsBox.innerHTML = "";
        $settingsBox.appendChild($imageSettingTemplateElement);
    })

    $imageUpload.addEventListener('change', e => {

        $imageSettingTemplateElement.querySelector('.image-preview').src = URL.createObjectURL(e.currentTarget.files[0])
    })

    $imageSettingTemplateElement.querySelector('#save').addEventListener('click', _=>{
        const submitForm = new FormData();

        submitForm.append('image', $imageUpload.files[0]);
        submitForm.append('currentUserId', store.getItem('currentUserId'))
        fetch('/api/image_upload.php', {method: 'POST', body: submitForm})
            .then(response => response.json())
            .then(body => {
                if (body.success) {
                    console.log('went well')
                }
            })
    })

    $settings.querySelector('#exit').addEventListener('click', _=>{
        window.location = './index.html'
    })




    $element.appendChild($settings)
}

const SettingsComponent = {
    mount
}
export default SettingsComponent
