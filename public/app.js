import LoginComponent from "./app/components/login.js";
import NoteListComponent from "./app/components/notelist.js";
import Store from "./app/store.js";
import NoteEditorComponent from "./app/components/noteeditor.js";
import Proxy from "./app/proxy.js";
import LogoutComponent from "./app/components/logout.js";
import Jobs from "./app/jobs.js";

const store = new Store();
const jobs = new Jobs();

export default function App($app) {
    // (new Proxy('serviceworker.js'))

    const list = new NoteListComponent.NoteList();

    const $notes = $app.querySelector('.notes');
    const $editor = $app.querySelector('.editor');

    NoteEditorComponent.mount($editor, list, store)
    NoteListComponent.mount($notes, list, store)
    LoginComponent.mount($app, jobs);
    LogoutComponent.mount($app, jobs);

    document.addEventListener('fetch-notes', async _ => {
        const json = await store.getItem('notes');

        if (json) {
            list.fromJSON(json);
        }

        document.dispatchEvent(new CustomEvent('render-notes'));
    });

    document.addEventListener('render-notes', _ => {
        NoteListComponent.mount($notes, list, store)
    });

}

