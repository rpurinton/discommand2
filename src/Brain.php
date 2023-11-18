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
        echo ("Brain Construct\n");
        parent::__construct();
        $this->loop = Loop::get();
        $this->bunny = new BunnyConsumer($this->loop, $myName, $this->inbox(...));
    }

    private function inbox($message): bool
    {
        echo ("Brain Inbox");
        print_r($message);
        return true;
    }
}
