<?php

namespace SilverStripePWA\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;

use SilverStripe\ORM\FieldType\DBBoolean;
use SilverStripePWA\Controllers\PushController;

class PushExtension extends DataExtension {

    private static $db = [
        'notification' => 'Boolean',
    ];
    

    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldToTab('Root.Main', CheckboxField::create('notification', 'Push-Notification', 1)
        ->setDescription('Do you want to send a push-notification when you publish this article?'),
        'Content');
    }

    static $has_written = false;

        function onAfterWrite() {
            parent::onAfterWrite();

            if( $this->owner->notification == false ) 
                return;
            if(self::$has_written) {

                // Payload of push-notification
                $dataArray = [
                    'title' => $this->owner->getTitle(),
                    'Message' => 'Find out more!',
                    'ttl' => 36000,
                    'icon' => 'https://pwa.violet88test2.nl/PWA/assets/Uploads/586f4e948c/Favicon-v3__FillWzUxMiw1MTJd.png',
                    'badge' => 'https://pwa.violet88test2.nl/PWA/assets/Uploads/586f4e948c/Favicon-v3__FillWzUxMiw1MTJd.png',
                    'url' => $this->owner->getAbsoluteLiveLink(false),
                    'vibrate' => [500,110,500,110,450,110,200,110,170,40,450,110,200,110,170,40,500],
                ];

                $data = json_encode($dataArray);
                $pushController = new PushController();
                $pushController->sendPush($data);
                self::$has_written = false;
                return;
            }
            
            self::$has_written = true;

        }
}
