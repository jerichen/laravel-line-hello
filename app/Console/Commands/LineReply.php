<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
