<?php

namespace SilverStripePWA\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;

class Subscriber extends DataObject
{
    private static $db = [
        'subscription' => 'Text'
    ];

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('subscription')

        );

        return $fields;
    }

}
