<?php

namespace SilverStripePWA\Controllers;

use SilverStripe\Control\Controller;
use SilverStripe\SiteConfig\SiteConfig;

class OfflineController extends Controller {

    /**
     * @var array
     */
    private static $allowed_actions = [
        'index'
    ];

    /**
     * Default controller action for the manifest.json file
     *
     * @return mixed
     */
    public function index($url) {
        $this->getResponse()->addHeader('Content-Type', 'text/html; charset="utf-8"');
        return $this->renderWith('Offline');
    }
}
