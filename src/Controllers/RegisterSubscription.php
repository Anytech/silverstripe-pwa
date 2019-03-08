<?php

namespace SilverStripePWA\Controllers;

use SilverStripePWA\Models\Subscriber;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;

class SubscriptionController extends Controller 
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

        $method = $request->httpMethod();

        switch ($method) {
            case 'POST':
                $subscription = json_decode($request->getBody(), true);

                $subscriber = new Subscriber();
                
                $subscriber->endpoint = $subscription['endpoint'];
                $subscriber->publicKey = $subscription['publicKey'];
                $subscriber->authToken = $subscription['authToken'];
                $subscriber->contentEncoding = $subscription['contentEncoding'];

                $subscriber->write();

                echo "Subscription added!";

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