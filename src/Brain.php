<?php

namespace RPurinton\Discommand2;

class Brain extends ConfigLoader
{
    private $bunny = null;

    public function __construct(private $myName)
    {
        parent::__construct();
        $this->bunny = new BunnyConsumer($this->myName);
        $this->bunny->publish($this->myName, ["name" => $this->myName, "type" => "register"]);
    }

    public function run()
    {
        $this->bunny->run($this->inbox(...));
    }

    private function inbox($message): bool
    {
        print_r($message);
        return true;
    }
}
