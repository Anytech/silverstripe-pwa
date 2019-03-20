let isSubscribed = false;
let swRegistration = null;
let applicationKey = "$PublicKey";
var debug = <% if $DebugMode %>true<% else %>false<% end_if %>;
var baseURL = "$BaseUrl";

// Console.log proxy for quick enabling/disabling
function log(msg) {
    if (debug) {
        console.log(msg);
    }
}

// Installing service worker
if ('serviceWorker' in navigator && 'PushManager' in window) {
    log('Service Worker and Push is supported');
    navigator.serviceWorker.register('service-worker.js')
        .then(function (swReg) {
            log('service worker registered');

            swRegistration = swReg;

            swRegistration.pushManager.getSubscription()
                .then(function (subscription) {
                    isSubscribed = !(subscription === null);

                    if (isSubscribed) {
                        log('User is allready subscribed');
                    } else {
                        swRegistration.pushManager.subscribe({
                                userVisibleOnly: true,
                                applicationServerKey: urlB64ToUint8Array(applicationKey)
                            })
                            .then(function (subscription) {
                                console.table(subscription);
                                log('User is subscribed');

                                saveSubscription(subscription);

                                isSubscribed = true;
                            })
                            .catch(function (err) {
                                log('Failed to subscribe user: ', err);
                            })
                    }
                })
        })
        .catch(function (error) {
            console.error('Service Worker Error', error);
        });
} else {
    console.warn('Push messaging is not supported');
}

// Save the subscription to the database via POST-request
function saveSubscription(subscription) {
    const key = subscription.getKey('p256dh');
    const token = subscription.getKey('auth');
    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];

    return fetch(baseURL + "add_subscription", {
        method: 'POST',
        body: JSON.stringify({
            endpoint: subscription.endpoint,
            publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
            authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
            contentEncoding,
        }),
    }).then(() => subscription);
}

// Base64 encryption
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
