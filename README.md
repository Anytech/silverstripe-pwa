# PWA module for SilverStripe

This module will add a Service Worker & Web Manifest to your SilverStripe Project. You can change the manifestsettings in the site-settings section inside the CMS.

## Functions

- Deploy a web-manifest from the CMS (Working)
- Service worker that displays offline-page when there is not connection (Working)
- Handle incomming push events (Working)
- Subscripe users to push-service (Working)
- Send push-notifications from CMS (In Progress)

## Requirements
- SilverStripe ^4.0
- silverware/colorpicker ^1.0
- silverstripe/vendor-plugin ^1.0
- silverstripe/restfulserver dev-master
- minishlink/web-push ^5.2

## Installation

```
composer require mdiederen/silverstripe-pwa
```

## Usage

- Run dev/build after the installation.
- Include the js to register the Service Worker
```
Requirements::javascript('mdiederen/silverstripe-pwa:resources/js/registerServiceWorker.js');
```
- Add this metadata to the header of the website
```
<meta name="theme-color" content="$SiteConfig.ManifestColor">
<link rel="manifest" href="{$BaseHref}manifest.json">
<script src="{$BaseHref}notifications.js"></script>

```
- You have to generate your own private & public key and put them in the _config directory for the VAPID authentication used by the push-manager. You can do this on unix (linux / MacOS) using the following commands:
```pseudocode
$ openssl ecparam -genkey -name prime256v1 -out private_key.pem
$ openssl ec -in private_key.pem -pubout -outform DER|tail -c 65|base64|tr -d '=' |tr '/+' '_-' >> public_key.txt
$ openssl ec -in private_key.pem -outform DER|tail -c +8|head -c 32|base64|tr -d '=' |tr '/+' '_-' >> private_key.txt
```

## License

See [License](LICENSE)

## Maintainers

Michiel Diederen - michiel@violet88.nl

