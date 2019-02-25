<?php

namespace SilverStripePWA\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverWare\Colorpicker\ORM\FieldType\DBColor;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverWare\Colorpicker\Forms\ColorField;
use SilverStripe\Forms\CheckboxField;

class PushNotificationsSiteConfigExtension extends DataExtension {

    private static $db = [
        'PushNotification' => 'Boolean'
    ];
    
    
    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldToTab('Root.PushNotifications', CheckboxField::create('PushNotification','Push Notification')->setDescription('Enable or disable push-notifications'));
    }
    

}
