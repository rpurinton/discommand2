<?php

namespace RPurinton\Discommand2;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;

class Brain extends ConfigLoader
{
    private LoopInterface $loop;
    private $bunny;

    public function __construct(private $myName)
    {
        Logger::log("Brain Construct");
        parent::__construct();
        $this->loop = Loop::get();
        $this->bunny = new BunnyConsumer($this->loop, $myName, $this->inbox(...));
    }

    private function inbox($message): bool
    {
        Logger::log("Brain Inbox");
        Logger::log(print_r($message, true));
        return true;
    }
}
