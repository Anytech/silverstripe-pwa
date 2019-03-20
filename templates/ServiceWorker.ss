const debug = <% if $DebugMode %>true<% else %>false<% end_if %>;
const baseURL = "$BaseUrl";
let notificationUrl = "$BaseUrl";

// Console.log proxy for quick enabling/disabling
function log(msg) {
    if (debug) {
        console.log(msg);
    }
}

// Install stage sets up the offline page in the cache and opens a new cache
self.addEventListener('install', function (event) {
    var offlinePage = new Request(baseURL + 'offline.html');
    event.waitUntil(
        fetch(offlinePage).then(function (response) {
            return caches.open('offline-page').then(function (cache) {
                log('Cached offline page during Install ' + response.url);
                return cache.put(offlinePage, response);
            });
        }));
});

// If any fetch fails, it will show the offline page.
self.addEventListener('fetch', function (event) {
    event.respondWith(
        fetch(event.request).catch(function (error) {
            log('Network request Failed. Serving offline page ' + error);
            return caches.open('offline-page').then(function (cache) {
                return cache.match(baseURL + 'offline.html');
            });
        }));
});

// This is a event that can be fired from your page to tell the SW to update the offline page
self.addEventListener('refreshOffline', function (response) {
    return caches.open('offline-page').then(function (cache) {
        log('Offline page updated from refreshOffline event: ' + response.url);
        return cache.put(offlinePage, response);
    });
});

// Listen for push-notifications and display them.
self.addEventListener('push', function (event) {
    log('Push received: ', event);
    let _data = event.data ? JSON.parse(event.data.text()) : {};
    notificationUrl = _data.url;
    event.waitUntil(
        self.registration.showNotification(_data.title, {
            body: _data.message,
            icon: _data.icon,
            badge: _data.badge,
            tag: _data.tag,
            vibrate: _data.vibrate
        })
    );
});

// Action when the user clicks on the notification
self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(clients.matchAll({
    type: "window"
  }).then(function(clientList) {
    for (var i = 0; i < clientList.length; i++) {
      var client = clientList[i];
      if (client.url == notificationUrl && 'focus' in client)
        return client.focus();
    }
    if (clients.openWindow)
      return clients.openWindow(notificationUrl);
  }));
});