<?php

namespace SilverStripePWA\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\SiteConfig\SiteConfig;

use SilverStripe\ORM\FieldType\DBBoolean;
use SilverStripePWA\Controllers\PushController;

class PushArticleExtension extends DataExtension {

    private static $db = [
        'notification' => 'Boolean',
    ];
    

    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldToTab('Root.Main', CheckboxField::create('notification', 'Push-Notification', 1)
        ->setDescription('Do you want to send a push-notification when you publish this article?'),
        'Content');
    }

        function onAfterPublish() {
            $config = SiteConfig::current_site_config();
            $icon = $config->icon();
            $badge = $config->badge();

            if( $this->owner->notification == false ) 
                return;
            
            if($icon && $icon->exists() && $badge && $badge->exists()){

                $payloadArray = [
                    'title' => $this->owner->getTitle(),
                    'Message' => $config->Message,
                    'ttl' => $config->ttl,
                    'icon' => $icon->fill(512,512)->Link(),
                    'badge' => $badge->fill(128,128)->Link(),
                    'url' => $this->owner->getAbsoluteLiveLink(false),
                    'vibrate' => [500,110,500,110,450,110,200,110,170,40,450,110,200,110,170,40,500],
                ];
            }
            
            $payload = json_encode($payloadArray);
            $pushController = new PushController();
            $pushController->sendPush($payload);
            
        }
}
