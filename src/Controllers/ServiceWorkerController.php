<?php

namespace SilverStripePWA\Controllers;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\Core\ClassInfo;

class ServiceWorkerController extends Controller {

    /**
     * @var array
     */
    private static $allowed_actions = [
        'index'
    ];
    
    /**
     * @config
     */
    private static $debug_mode = false;

    /**
     * Default controller action for the service-worker.js file
     *
     * @return mixed
     */
    public function index($url) {
        $this->getResponse()->addHeader('Content-Type', 'application/javascript; charset="utf-8"');
        return $this->renderWith('ServiceWorker');
    }
    
    /**
     * Base URL
     * @return varchar
     */
    public function BaseUrl() {
        return Director::baseURL();
    }

    
    /**
     * Public Key
     * @return varchar
     */
    public function PublicKey() {
        $key = (string)file_get_contents(__DIR__ . "/../../_config/public_key.txt");
        return substr(trim($key), 0);;

    }
    /**
     * Debug mode
     * @return bool
     */
    public function DebugMode() {
        if(Director::isDev()){
            return true;
        }
        return $this->config()->get('debug_mode');
    }

}
