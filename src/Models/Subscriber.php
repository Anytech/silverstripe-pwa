<?php

namespace SilverStripePWA\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;

class Subscriber extends DataObject
{
    private static $db = [
        'endpoint' => 'Text',
        'publicKey' => 'Text',
        'authToken' => 'Text',
        'contentEncoding' => 'Text'
    ];

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('endpoint'),
            TextField::create('publicKey'),
            TextField::create('authToken'),
            TextField::create('contentEncoding')

        );

        return $fields;
    }

}