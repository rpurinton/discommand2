<?php

namespace RPurinton\Discommand2;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand2\Exceptions\ConfigurationException;
use RPurinton\Discommand2\Exceptions\LogException;

class Brain extends ConfigLoader
{
    private LoopInterface $loop;
    private $bunny;
    private $logger;

    public function __construct(private $myName)
    {
        try {
            parent::__construct();
            $this->logger = new Logger("/home/$myName/logs.d");
            $this->loop = Loop::get();
            $this->bunny = new BunnyConsumer($this->loop, $myName, $this->inbox(...));
        } catch (ConfigurationException | LogException $e) {
            // Handle exception (log or rethrow)
            throw $e;
        }
    }

    private function inbox(string $message): bool
    {
        try {
            $this->logger->log("Received message: $message");
            return true;
        } catch (LogException $e) {
            // Handle logging exception
            throw $e;
        }
    }
}
