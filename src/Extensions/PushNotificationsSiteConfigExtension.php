<?php

namespace SilverStripePWA\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\TextField;

class PushNotificationsSiteConfigExtension extends DataExtension {
    
    // Set payload for push-notification
    private static $db = [
        'Message' => 'Text',
        'ttl' => 'Int',
        'vibrate' => 'Text',
    ];

    private static $has_one = [
        'icon' => Image::class,
        'badge' => Image::class
    ];
    
    private static $vibrationPatterns = [
        '[500,110,500,110,450,110,200,110,170,40,450,110,200,110,170,40,500]',
        'none'
    ];

    public function onAfterWrite() {
        parent::onAfterWrite();
        $icon = $this->owner->icon();
        $badge = $this->owner->badge();

        if ($icon && $icon->exists() && $badge && $badge->exists()) {
            $icon->doPublish();
            $badge->doPublish();
        }
    }
    
    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldToTab('Root.PushNotifications', TextField::create('Message', 'Message')->setDescription('Set the message thats displayed in the body of the notification'));
        $fields->addFieldToTab('Root.PushNotifications', TextField::create('ttl', 'Expiration Time')->setDescription('Set the expiration time in seconds'));
        $fields->addFieldToTab('Root.PushNotifications', DropdownField::create('vibrate', 'Vibration Pattern', array_combine(self::$vibrationPatterns, self::$vibrationPatterns))->setDescription('Choose a vibration pattern'));
        $fields->addFieldToTab('Root.PushNotifications', UploadField::create('icon', 'Icon')->setDescription('This image must at least 512x512px'));
        $fields->addFieldToTab('Root.PushNotifications', UploadField::create('badge', 'Badge')->setDescription('This image should be a small monochrome icon and at least 128x128px'));

    }
    
}