<?php

namespace App\Console\Commands;

use App\Services\ConsumerService;
use Illuminate\Console\Command;

class MQRequestConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mq:request-consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Request Order Consumer Command';

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
     * @return int
     */
    public function handle()
    {
        $mqService = new ConsumerService();
        $mqService->consumerMessage();
    }
}
