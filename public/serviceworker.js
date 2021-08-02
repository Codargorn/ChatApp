const PRECACHE = 'v1.0.0';
const RUNTIME = 'runtime';

// Service-Worker wird einmalig installiert, dann nur noch wenn Unterschiede zu vorherigem Service-Worker
self.addEventListener('install', event => {
    // Event soll warten bis...
    event.waitUntil(
        // erstellt einen neuen Cache mit dem Namen PRECACHE
        caches.open(PRECACHE)
            // Promise welches ein CacheObject returned, zu cachende files werden übergeben
            .then(cache => cache.addAll([
                'https://cdn.jsdelivr.net/npm/bootstrap-dark-5@1.0.1/dist/css/bootstrap-night.min.css',
                'https://cdn.jsdelivr.net/npm/marked/marked.min.js',
                '/app.js',
                '/app/html.js',
                '/app/proxy.js',
                '/app/store.js',
                '/app/components/login.js',
                '/app/components/logout.js',
                '/app/components/note.js',
                '/app/components/noteeditor.js',
                '/app/components/notelist.js',
                '/css/style.css',
                '/css/fontawesome-free-5.15.3-web/css/all.css',
                'index.html',
                './'
            ]))
            // nicht auf die Antwort antworten
            .then(self.skipWaiting())
    );
});

//wird aufgerufen beim aktivieren eines neuen Service-Workers , zum updaten
self.addEventListener('activate', event => {
    const currentCaches = [PRECACHE, RUNTIME];
    // Event soll warten bis...
    event.waitUntil(
        // gibt Promise(Array) zurück mit allen Cache Items
        caches.keys().then(cacheNames => {
            // gibt ein neues Array zurück mit allen veralteten Cache-Items
            return cacheNames.filter(cacheName => !currentCaches.includes(cacheName));
        }).then(cachesToDelete => {
            // erzeugt Promise und geht das Array der veralteten Cache-Items durch
            return Promise.all(cachesToDelete.map(cacheToDelete => {
                // löscht das veraltete Cache-Item gibt Promise mit true zurück wenn erfolgreich
                return caches.delete(cacheToDelete);
            }));
            // alle Seiten akzeptieren den neuen Service-Worker ohne neuzuladen
        }).then(() => self.clients.claim())
    );
});


// wird jedesmal aufgerufen wenn im Code fetch verwendet wird
self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') {
        return;
    }
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }
        event.respondWith(
            caches.match(event.request).then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }

                return caches.open(RUNTIME).then(cache => {
                    return fetch(event.request).then(response => {
                        return cache.put(event.request, response.clone()).then(() => {
                            return response;
                        });
                    });
                });
            })
        );
});