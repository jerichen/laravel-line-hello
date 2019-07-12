<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Log;
use App\Models\Entities\LineUser;

class LineController extends Controller
{
    protected $channel_access_token;
    protected $password;
    protected $headers;

    public function __construct()
    {
        $this->channel_access_token = env('LINE_CHANNEL_ACCESS_TOKEN');
        $this->password = 'openpushme';
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

            // bot dirty logic
            if($event['message']['type'] === 'text'){
                if ($event['message']['text'] === $this->password) {

                    $line_user = LineUser::updateOrCreate(
                        ['user_id' => $event['source']['userId']],
                        [
                            'reply_token' => $event['replyToken'],
                            'message' => $event['message']['text'],
                        ]
                    );
                    Log::info("line_user: " . print_r($line_user, true));

                    $reply_message = 'Login Success! Wellcome!';

                } else {
                    $reply_message = 'Input password please.';
                }
            }

            $post_params = [
                'replyToken' => $event['replyToken'],
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => $reply_message
                    ]
                ]
            ];

            Log::info(date('Y-m-d h:i:s') . ' line Reply start');

            $url = 'https://api.line.me/v2/bot/message/reply';
            $method = 'POST';
            $client = new Client();
            $post_data = [
                RequestOptions::JSON => $post_params,
                RequestOptions::HEADERS => $this->headers,
                'User-Agent' => 'JeriBot',
            ];

            $response = $client->request($method, $url, $post_data);
            $response_status_code = $response->getStatusCode();

            Log::info("line-push-response-status-code: " . $response_status_code);
            Log::info(date('Y-m-d h:i:s') . ' line Reply end');
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

            Log::info(date('Y-m-d h:i:s') . ' line Reply start');

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
            Log::info(date('Y-m-d h:i:s') . ' line Reply end');
        }
    }
}
