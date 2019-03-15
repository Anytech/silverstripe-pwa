<?php

use SilverStripePWA\Models\Subscriber;
use SilverStripe\Dev\SapphireTest;

class SubscriberTest extends SapphireTest
{
    public function testCreate()
    {
        $subscribers = Subscriber::get()->count();
        $SubscriberInstance = Subscriber::Create();
        $SubscriberObject = [
                'endpoint' => 'Test123',
                'publicKey' => 'Test123',
                'authToken' => 'Test123',
                'contentEncoding' => 'Test123'
        ];

        $SubscriberInstance->endpoint = $SubscriberObject['endpoint'];
        $SubscriberInstance->publicKey = $SubscriberObject['publicKey'];
        $SubscriberInstance->authToken = $SubscriberObject['authToken'];
        $SubscriberInstance->contentEncoding = $SubscriberObject['contentEncoding'];
        $SubscriberInstance->write();

        $this->assertEquals($subscribers +1, Subscriber::get()->count());
    }
}