<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Log;

use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;

class LineController extends Controller
{
    protected $channel_access_token;
    protected $password;
    protected $headers;

    private $client;
    private $bot;
    private $events;
    private $replyToken;
    private $text;
    private $to;

    public function __construct(Request $request)
    {
        $this->client = new CurlHTTPClient(env('LINE_CHANNEL_ACCESS_TOKEN'));
        $this->bot = new LINEBot($this->client, ['channelSecret' => env('LINE_CHANNEL_SECRET'));
        $signature = $request->header(HTTPHeader::LINE_SIGNATURE);
        if (!empty($signature)) {
            $this->events = $this->bot->parseEventRequest($request->getContent(), $signature);
        }

//        $this->channel_access_token = env('LINE_CHANNEL_ACCESS_TOKEN');
//        $this->password = 'opendoor';
//        $this->headers = [
//            'Content-Type' => 'application/json',
//            'Authorization' => "Bearer {$this->channel_access_token}",
//        ];
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
}
