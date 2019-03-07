<?php

namespace SilverStripePWA\Controllers;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;

use SilverStripePWA\Models\Subscriber;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Control\HTTPRequest;

class PushNotificationController extends Controller 
{
    /**
     * @var array
     */
    private static $allowed_actions = [
        'index'
    ];

    /**
     * Default controller action
     *
     * @return mixed
     */
    public function index(HTTPRequest $request) {
        $subscribers = Subscriber::get();

        // TO-DO lees keys uit bestanden
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:michiel@violet88.nl',
                'publicKey' => 'BOyWSndPyP1kHhyqTY4Zqm6lL1cbTSotBHkqv1G1sLzO18xU5oF4uPS3AoQhhe9O5gJbhwMDueFSTipS6AFzK5g=',
                'privateKey' => '+rLOL2cF6KCdzNjOMukgNNgf09PbtA+kiJk0QIjzJm0=',
            ],
        ];

        $webPush = new WebPush($auth);

        $method = $request->httpMethod();

        switch ($method) {
            case 'POST':
                $payload = $request->getBody();


                foreach($subscribers as $subscriber) {

                   $jsonSubscription = $subscriber->subscription;
                   $subscription = json_decode($jsonSubscription, true);

                   $sub = Subscription::create($subscription);

                   $res = $webPush->sendNotification($sub, $payload);
            
                }

                foreach ($webPush->flush() as $report) {
                    $endpoint = $report->getRequest()->getUri()->__toString();
                    $this->getResponse()->addHeader('Content-Type', 'application/json; charset="utf-8"');

                    if ($report->isSuccess()) {
                        $succes = "Message sent successfully for subscription {$endpoint}.";
                        return json_encode($succes);
                    } else {
                        // TO-DO verwijderen van abonnee uit DB als deze niet meer actief is.
                        $error =  "Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
                        return json_encode($error);

                    }
                } 
                break;
            case 'PUT':
                echo "Error: PUT-method not handled";
                break;
            case 'DELETE':
                echo "Error: DELETE-method not handled";
                break;
            case 'GET':
                echo "Error: GET-method not handled";
                break;
            default:
                echo "Error: method not handled";
                return;
            }
    }
}

