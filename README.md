# PWA module for SilverStripe

This module will add a Service Worker & Web Manifest to your SilverStripe Project. You can change the manifest settings in the site-settings section inside the CMS.

## Functions

- Deploy a web-manifest from the CMS (Working)
- Service worker that caches website for faster loading times (Working)
- Handle push events (In Progress)
- Send push-notifications from CMS (In Progress)

## Requirements
- SilverStripe ^4.0
- silverware/colorpicker ^1.0
- silverstripe/vendor-plugin ^1.0
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
```
## License

See [License](LICENSE)

## Maintainers

Michiel Diederen - michiel@violet88.nl