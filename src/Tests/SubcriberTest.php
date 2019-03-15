<?php
namespace SilverStripePWA\Tests;

use SilverStripePWA\Models\Subscriber;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\ORM\DataObject;
use Page;
use PHPUnit\Framework\TestCase;


class SubscriberTest extends TestCase
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