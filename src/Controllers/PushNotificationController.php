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

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:michiel@violet88.nl',
                'publicKey' => file_get_contents(__DIR__ . "/../../_config/public_key.txt"),
                'privateKey' => file_get_contents(__DIR__ . "/../../_config/private_key.txt"),           
            ],
        ];

        $webPush = new WebPush($auth);

        $method = $request->httpMethod();

        switch ($method) {
            case 'POST':
                $payload = $request->getBody();

                foreach($subscribers as $subscriber) {
                    
                    $subscriptionArray = [
                        'endpoint' => $subscriber->endpoint,
                        'publicKey' => $subscriber->publicKey,
                        'authToken' => $subscriber->authToken,
                        'contentEncoding' => $subscriber->contentEncoding
                    ];

                    $subscription = Subscription::create($subscriptionArray);

                    $sent = $webPush->sendNotification($subscription, $payload);
            
                }

                $response = [];

                foreach ($webPush->flush() as $report) {
                    $endpoint = $report->getRequest()->getUri()->__toString();
                    $this->getResponse()->addHeader('Content-Type', 'application/json; charset="utf-8"');
                    
                    if ($report->isSuccess()) {
                        $response[$endpoint] = "Pushed to client!";
                    } else {
                        $isTheEndpointWrongOrExpired = $report->isSubscriptionExpired();
                        if($isTheEndpointWrongOrExpired) {
                            $response[$endpoint] = "Push Failed, device no longer subscribed.";

                        } else {
                            $response[$endpoint] = "Push Failed :( {$report->getReason()}";

                        }
                    }
                }

                return json_encode($response);
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

