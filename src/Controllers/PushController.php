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
        $subscribers = Subscriber::get();

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:michiel@violet88.nl',
                'publicKey' => file_get_contents(__DIR__ . "/../../_config/public_key.txt"),
                'privateKey' => file_get_contents(__DIR__ . "/../../_config/private_key.txt"),           
            ],
        ];

        $webPush = new WebPush($auth);

        $payload = $content;

                foreach($subscribers as $subscriber) {
                    
                    $subscriberArray = [
                        'endpoint' => $subscriber->endpoint,
                        'publicKey' => $subscriber->publicKey,
                        'authToken' => $subscriber->authToken,
                        'contentEncoding' => $subscriber->contentEncoding
                    ];

                    $sub = Subscription::create($subscriberArray);


                    $sent = $webPush->sendNotification($sub, $payload);
            
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

                            // delete subscriber from db
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
