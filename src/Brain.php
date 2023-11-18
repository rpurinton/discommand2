<?php

namespace RPurinton\Discommand2;

class Brain extends ConfigLoader
{
    private $bunny;

    public function __construct(private $myName)
    {
        echo ("Brain Construct\n");
        parent::__construct();
        $this->bunny = new BunnyConsumer;
    }

    public function run()
    {
        echo ("Brain Run\n");
        $this->bunny->run($this->myName, $this->inbox(...));
    }

    private function inbox($message): bool
    {
        echo ("Brain Inbox");
        print_r($message);
        return true;
    }
}
