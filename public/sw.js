const CACHE_NAME = 'gestao-cdi-v1';
const STATIC_ASSETS = [
  '/',
  '/manifest.json',
];

// Install: cacheia assets estáticos
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
  );
  self.skipWaiting();
});

// Activate: limpa caches antigos
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
    )
  );
  self.clients.claim();
});

// Fetch: network-first para páginas, cache-first para assets
self.addEventListener('fetch', (event) => {
  const { request } = event;

  // Ignora POST, APIs e rotas de auth
  if (request.method !== 'GET') return;
  if (request.url.includes('/login') || request.url.includes('/register')) return;

  // Assets estáticos (CSS, JS, fonts, images) → cache-first
  if (request.destination === 'style' || request.destination === 'script' ||
      request.destination === 'font' || request.destination === 'image') {
    event.respondWith(
      caches.match(request).then((cached) =>
        cached || fetch(request).then((response) => {
          const clone = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
          return response;
        })
      )
    );
    return;
  }

  // Páginas HTML → network-first com fallback para cache
  event.respondWith(
    fetch(request)
      .then((response) => {
        const clone = response.clone();
        caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
        return response;
      })
      .catch(() => caches.match(request).then((cached) => cached || caches.match('/offline')))
  );
});
