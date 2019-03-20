# PWA module for SilverStripe

This module will add a Service Worker & Web Manifest to your SilverStripe Project. You can change the manifestsettings in the site-settings section inside the CMS.

## Functions

- Deploy a web-manifest from the CMS **Working**
- Service worker that displays offline-page when there is not connection **Working**
- Handle incomming push events **Working**
- Subscribe devices to push-service **Working**
- Send push-notifications from CMS **Working**
- Change notification-settings in CMS **Working**
- Different kinds of service workers (Offline-page, Cache-first, Pre-cache) **In Development**

## Requirements
- SilverStripe ^4.0
- silverware/colorpicker ^1.0
- silverstripe/vendor-plugin ^1.0
- silverstripe/restfulserver dev-master
- minishlink/web-push ^5.2

## Installation
**Not working right now - Repo is private**

```console
composer require mdiederen/silverstripe-pwa
```

## Usage

- Run dev/build after the installation.
- Add this metadata to the header of the website
```html
<meta name="theme-color" content="$SiteConfig.ManifestColor">
<link rel="manifest" href="{$BaseHref}manifest.json">
<script src="{$BaseHref}RegisterServiceWorker.js"></script>

```
- You have to generate your own private & public key and put them in the `/vendor/mdiederen/silverstripe-pwa/_config` directory for the VAPID authentication used by the push-manager. You can do this on unix (linux / MacOS) using the following commands:
```console
$ openssl ecparam -genkey -name prime256v1 -out private_key.pem
$ openssl ec -in private_key.pem -pubout -outform DER|tail -c 65|base64|tr -d '=' |tr '/+' '_-' >> public_key.txt
$ openssl ec -in private_key.pem -outform DER|tail -c +8|head -c 32|base64|tr -d '=' |tr '/+' '_-' >> private_key.txt
```

- Add the extension to the pages on which you want to enable push-notifications in the `config.yml` inside the `vendor/mdiederen/silverstripe-pwa/_config` directory.

## License

See [License](LICENSE)

## Maintainers

Michiel Diederen - michiel@violet88.nl
