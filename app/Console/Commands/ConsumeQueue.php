<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ConsumerService;

class ConsumeQueue extends Command
{
    protected $signature = 'mq:consume-queue';
    protected $description = 'Consume messages from the queue';

    protected $consumerService;

    public function __construct(ConsumerService $consumerService)
    {
        parent::__construct();
        $this->consumerService = $consumerService;
    }

    public function handle()
    {
        $this->consumerService->consumerMessage();
    }
}
