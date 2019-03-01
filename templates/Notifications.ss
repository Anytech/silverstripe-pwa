let isSubscribed = false;
let swRegistration = null;
let applicationKey = "BILv757RtZendMguPVvhGSs50ZYq8MxVCrtTbtMqapIA6UPr7KD7LnRUXFWvedqtNG7bcWZ4WQm2zycmgZbuOXw";
var baseURL = $BaseUrl;


// Url Encription
function urlB64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

// Installing service worker
if ('serviceWorker' in navigator && 'PushManager' in window) {
    console.log('Service Worker and Push is supported');
    navigator.serviceWorker.register('service-worker.js')
        .then(function (swReg) {
            console.log('service worker registered');

            swRegistration = swReg;

            swRegistration.pushManager.getSubscription()
                .then(function (subscription) {
                    isSubscribed = !(subscription === null);

                    if (isSubscribed) {
                        console.log('User is allready subscribed');
                    } else {
                        swRegistration.pushManager.subscribe({
                                userVisibleOnly: true,
                                applicationServerKey: urlB64ToUint8Array(applicationKey)
                            })
                            .then(function (subscription) {
                                console.table(subscription);
                                console.log('User is subscribed');

                                saveSubscription(subscription);

                                isSubscribed = true;
                            })
                            .catch(function (err) {
                                console.log('Failed to subscribe user: ', err);
                            })
                    }
                })
        })
        .catch(function (error) {
            console.error('Service Worker Error', error);
        });
    }
else {
    console.warn('Push messaging is not supported');
}

// Send request to database for add new subscriber
function saveSubscription(subscription) {
    let xmlHttp = new XMLHttpRequest();
    const key = subscription.getKey('p256dh');
    const token = subscription.getKey('auth');
    const endpoint = subscription.endpoint;

    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];
    const publicKey = key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null;
    const authToken = token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null;

    console.log(key, token, endpoint);

    xmlHttp.open("POST", baseURL + "/api/v1/SilverStripe-PushNotifications-Subscription/");
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState != 4) return;
        if (xmlHttp.status != 201 && xmlHttp.status != 304) {
            console.log('HTTP error ' + xmlHttp.status, null);
        } else {
            console.log("User subscribed to server");
        }
    };
    xmlHttp.send("endpoint="+endpoint+"&p256dh="+publicKey+"&auth="+authToken);
}

//If any fetch fails, it will show the offline page.
self.addEventListener('fetch', function (event) {
    event.respondWith(
        fetch(event.request).catch(function (error) {
            console.log('Network request Failed. Serving offline page ' + error);
            return caches.open('offlinePage').then(function (cache) {
                return cache.match(baseURL+'/offline.html');
            });
        }));
});

//This is a event that can be fired from your page to tell the SW to update the offline page
self.addEventListener('refreshOffline', function (response) {
    return caches.open('offlinePage').then(function (cache) {
        console.log('Offline page updated from refreshOffline event: ' + response.url);
        return cache.put(offlinePage, response);
    });
});

// Listen for push-notifications and display them.
self.addEventListener('push', function (event) {
    console.log('Push received: ', event);
    let _data = event.data ? JSON.parse(event.data.text()) : {};
    notificationUrl = _data.url;
    event.waitUntil(
        self.registration.showNotification(_data.title, {
            body: _data.message,
            icon: _data.icon,
            tag: _data.tag
        })
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    event.waitUntil(
        clients.matchAll({
            type: "window"
        })
        .then(function (clientList) {
            if (clients.openWindow) {
                return clients.openWindow(notificationUrl);
            }
        })
    );
});