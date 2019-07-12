<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Log;
use App\Models\Entities\LineUser;

class LinePush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'line:push {url} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Line Push';

    protected $channel_access_token;
    protected $password;
    protected $headers;

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

    public function handle()
    {
        Log::info(date('Y-m-d h:i:s').' line push start');

        $url = $this->argument('url');
        $message = $this->argument('message');
        $user_ids = LineUser::all()->pluck('user_id');

        $post_params = [
            'to' => $user_ids,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $url . $message,
                ]
            ]
        ];

        $url = 'https://api.line.me/v2/bot/message/multicast';
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
        Log::info(date('Y-m-d h:i:s').' line push end');
    }
}
