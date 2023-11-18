<?php

namespace RPurinton\Discommand2;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;

class Brain extends ConfigLoader
{
    private LoopInterface $loop;
    private $bunny;
    private $logger;

    public function __construct(private $myName)
    {
        parent::__construct();
        $this->logger = new Logger($this->config["logger"]["log_dir"] ?? __DIR__ . '/../logs.d');
        $this->loop = Loop::get();
        $this->bunny = new BunnyConsumer($this->loop, $myName, $this->inbox(...));
    }

    private function inbox($message): bool
    {
        $this->logger->log("Received message: " . json_encode($message, JSON_PRETTY_PRINT));
        return true;
    }
}
