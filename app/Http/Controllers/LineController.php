<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
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
        $body_msg = file_get_contents('php://input');
        Log::info("body_msg: " . print_r($body_msg, true));

        $obj = json_decode($body_msg, true);
        Log::info("obj: " . print_r($obj, true));

        foreach ($obj['events'] as &$event) {
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

//        $body_msg = file_get_contents('php://input');
        $post_params = [
            'replyToken' => 'U7fe6e83736d4b24979f8d2f7027e4652',
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
    }
}
