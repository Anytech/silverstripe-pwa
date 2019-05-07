<?php
namespace SilverStripePWA\Controllers;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Controller;

use SilverStripePWA\Models\Subscriber;

class PushController extends Controller
{
    public function sendPush($content)
    {

        // Get subs from db
        $subscribers = Subscriber::get();

        // Create VAPID (Voluntary Application Server Identification) [optional - required for sending notification with payload]
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:michiel@violet88.nl',
                'publicKey' => file_get_contents(__DIR__ . "/../../_config/public_key.txt"),
                'privateKey' => file_get_contents(__DIR__ . "/../../_config/private_key.txt"),           
            ],
        ];
        // Create new WebPush with authentication
        $webPush = new WebPush($auth);

        // Get content from parameter
        $payload = $content;

        // Loop through subcribers & create new subscription -> send push to subscription
        foreach($subscribers as $subscriber) {
            
            $subscriberArray = [
                'endpoint' => $subscriber->endpoint,
                'publicKey' => $subscriber->publicKey,
                'authToken' => $subscriber->authToken,
                'contentEncoding' => $subscriber->contentEncoding
            ];

            $sub = Subscription::create($subscriberArray);

            // Push it!
            $sent = $webPush->sendNotification($sub, $payload);
    
        }

        // Get respons from subscription & react accordingly
        $response = [];
        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();
            $this->getResponse()->addHeader('Content-Type', 'application/json; charset="utf-8"');
            
            if ($report->isSuccess()) {
                $response[$endpoint] = "Pushed to client!";
            } else {
                $isTheEndpointWrongOrExpired = $report->isSubscriptionExpired();
                if($isTheEndpointWrongOrExpired) {
                    // Delete subscriber from db
                    $subscription = $subscribers->find('endpoint', $endpoint);
                    $subscription->delete();
                    $response[$endpoint] = "Push Failed, device no longer subscribed -> subscription removed from DB";
                } else {
                    $response[$endpoint] = "Push Failed :( {$report->getReason()}";
                }
            }
        }
                 
        return json_encode($response);
    }
}
