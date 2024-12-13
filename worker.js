var CACHE_NAME = 'cacheFrases';
var urlsToCache = [
    '/',
    '/estilos/principal.css',
    '/frases/frases.json',
    '/js/main.js'
];
self.addEventListener('install', function (event) { // También se puede usar this
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function (cache) {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request)
            .then(function (response) {
                /* caches.match () siempre se resuelve, pero en
                caso de éxito la respuesta tendrá valor */
                if (response) {
                    return response;
                } else {
                    return fetch(event.request).then(function (response) {
                        /* La respuesta puede usarse solo una vez, necesitamos
                        guardar el clon para poner una copia en la caché */
                        let responseClone = response.clone();
                        caches.open(CACHE_NAME).then(function (cache) {
                            cache.put(event.request, responseClone);
                        });
                        return response;
                    }).catch(function (err) {
                        console.error('ERROR: ' + err);
                    });
                }
            }));
});


self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request)
            .then(function (response) {
                if (response) {
                    return response;
                }
                // IMPORTANTE: Clona la petición.
                var fetchRequest = event.request.clone();
                return fetch(fetchRequest).then(
                    function (response) {
                        // Chequeamos si recibimos una respuesta válida.
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }
                        // IMPORTANTE: Clonamos la respuesta.
                        var responseToCache = response.clone();
                        caches.open(CACHE_NAME)
                            .then(function (cache) {
                                cache.put(event.request, responseToCache); // Actualizamos la cache.
                            });
                        return response;
                    }
                );
            })
    );
});
