# PWA module for SilverStripe

This module will add a Service Worker & Web Manifest to your SilverStripe Project. You can change the manifestsettings in the site-settings section inside the CMS.

## Functions

- Deploy a web-manifest from the CMS <span style="color:green">**Working**</span>
- Service worker that displays offline-page when there is not connection <span style="color:green">**Working**</span>
- Handle incomming push events <span style="color:green">**Working**</span>
- Subscribe devices to push-service <span style="color:green">**Working**</span>
- Send push-notifications from CMS <span style="color:green">**Working**</span>
- Change notification-settings in CMS <span style="color:green">**Working**</span>
- Different kinds of service workers (Offline-page, Cache-first, Pre-cache) <span style="color:orange">**In Development**</span>

## Requirements
- SilverStripe ^4.0
- silverware/colorpicker ^1.0
- silverstripe/vendor-plugin ^1.0
- minishlink/web-push ^5.2

## Installation
<span style="color:red">**Not working right now - Repo is private**</span>

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

Windows user can use online tools like [Vapid Key Generator](https://tools.reactpwa.com/vapid)

- Add the extension to the pages on which you want to enable push-notifications in the `config.yml` inside the `vendor/mdiederen/silverstripe-pwa/_config` directory.

## License

See [License](LICENSE)

## Maintainers

Michiel Diederen - michiel@violet88.nl
