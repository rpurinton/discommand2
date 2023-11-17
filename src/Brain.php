<?php

namespace RPurinton\Discommand2;

require_once(__DIR__ . "/BunnyConsumer.php");

class Brain extends ConfigLoader
{
    public function __construct(private $myName)
    {
        parent::__construct();
        new BunnyConsumer($this->myName, $this->inbox(...));
    }

    private function inbox(string $message): bool
    {
        echo "Received: " . json_encode($message) . "\n";
        return true;
    }
}
