<?php

namespace SilverStripePWA\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Permission;


class Subscription extends DataObject
{

    private static $db = [
        'endpoint' => 'Text',
        'p256dh' => 'Text',
        'auth' => 'Text',
    ];

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('endpoint'),
            TextField::create('p256dh'),
            TextField::create('auth')
        );

        return $fields;
    }

    private static $api_access = [
        'view' => ['endpoint', 'p256dh', 'auth'],
        'edit' => ['endpoint', 'p256dh', 'auth'],
        'create' => ['endpoint', 'p256dh', 'auth']
    ];

    function canView($member = null) {
        return true;
    }

    function canEdit($member = null) {
        return true;
    }

    function canCreate($member = null, $context = []) {
        return true;
    }
}
