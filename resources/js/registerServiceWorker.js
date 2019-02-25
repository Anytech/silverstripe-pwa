if ('serviceWorker' in navigator) {
    var baseHref = (document.getElementsByTagName('base')[0] || {}).href;
    if (baseHref) {
        navigator.serviceWorker.register(baseHref + 'service-worker.js', {
            scope: './'
        }).then(function (reg, err) {
            if(err) console.error('service worker could not be registered ${err}')
            console.log('Service worker has been registered for scope:' + reg.scope);
        });
    }
}