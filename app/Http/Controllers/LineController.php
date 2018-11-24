<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Log;

class LineController extends Controller
{
    protected $channel_access_token;
    protected $password;
    protected $headers;

    public function __construct()
    {
        $this->channel_access_token = env('LINE_CHANNEL_ACCESS_TOKEN');
        $this->password = 'opendoor';
        $this->headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->channel_access_token}",
        ];
    }

    public function reply(Request $request)
    {
        $events = $request->get('events');
        foreach ($events as $event) {
            Log::info("event: " . print_r($event, true));

            $post_params = [
                'replyToken' => $event['replyToken'],
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => 'Login Success! Wellcome!'
                    ]
                ]
            ];

            Log::info(date('Y-m-d h:i:s').' line Reply start');

            $url = 'https://api.line.me/v2/bot/message/reply';
            $method = 'POST';
            $client = new Client();
            $data = [
                RequestOptions::JSON => $post_params,
                RequestOptions::HEADERS => $this->headers,
                'User-Agent' => 'JeriBot',
            ];

            $response = $client->request($method, $url, $data);
            $response_status_code = $response->getStatusCode();

            Log::info("line-push-response-status-code: " . $response_status_code);
            Log::info(date('Y-m-d h:i:s').' line Reply end');
        }
    }

    public function webhook(Request $request)
    {
        $events = $request->get('events');
        foreach ($events as $event) {
            Log::info("event: " . print_r($event, true));

            $post_params = [
                'replyToken' => $event['replyToken'],
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => 'Login Success! Wellcome!'
                    ]
                ]
            ];

            Log::info(date('Y-m-d h:i:s').' line Reply start');

            $url = 'https://api.line.me/v2/bot/message/reply';
            $method = 'POST';
            $client = new Client();
            $data = [
                RequestOptions::JSON => $post_params,
                RequestOptions::HEADERS => $this->headers,
                'User-Agent' => 'JeriBot',
            ];

            $response = $client->request($method, $url, $data);
            $response_status_code = $response->getStatusCode();

            Log::info("line-push-response-status-code: " . $response_status_code);
            Log::info(date('Y-m-d h:i:s').' line Reply end');
        }
    }
}
