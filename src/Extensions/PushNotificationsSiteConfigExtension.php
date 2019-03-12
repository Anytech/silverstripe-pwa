<?php

namespace SilverStripePWA\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;

class PushNotificationsSiteConfigExtension extends DataExtension {

    private static $db = [
        'PushNotification' => 'Boolean'
    ];
    

    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldToTab('Root.PushNotifications', CheckboxField::create('PushNotification','Push Notification')->setDescription('Enable or disable push-notifications'));

    }
    
}
