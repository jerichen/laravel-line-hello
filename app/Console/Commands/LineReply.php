<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Log;
use App\Models\Entities\LineUser;

class LineReply extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'line-reply';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Line Reply';

    protected $channel_access_token;
    protected $headers;
    protected $password;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->channel_access_token = env('LINE_CHANNEL_ACCESS_TOKEN');
        $this->password = 'openpushme';
        $this->headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->channel_access_token}",
        ];
    }

    private function getRequestEvents()
    {
        $events = collect();
        $events->put('type', 'message');
        $events->put('replyToken', '5bed01d89baf42bdb5256804c79e9f06');

        $source = [
          'userId' => 'U7fe6e83736d4b24979f8d2f7027e4652',
          'type' => 'user',
        ];
        $events->put('source', $source);
        $events->put('timestamp', '1543075191535');
        $message = [
            'type' => 'text',
            'id' => '8910676839415',
            'text' => 'test',
        ];
        $events->put('message', $message);

        return array($events);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $events = $this->getRequestEvents();
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
}
