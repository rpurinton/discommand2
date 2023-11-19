<?php

namespace RPurinton\Discommand2;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand2\Modules\RabbitMQ;

class Brain extends SqlClient
{
    private LoopInterface $loop;
    private $modules = [];

    public function __construct($myName)
    {
        try {
            parent::__construct($myName);
            $this->loop = Loop::get();
            $this->modules["bunny"] = new RabbitMQ($this->config["bunny"] ?? [], $this->loop, $myName, $this->inbox(...));
        } catch (\Throwable $e) {
            // Handle other exceptions
            throw $e;
        } finally {
            $this->logger->log("$myName is alive.");
            return $this;
        }
    }

    public function __destruct()
    {
        $this->loop->stop();
        parent::__destruct();
    }

    private function inbox(array $message): bool
    {
        try {
            $this->logger->log("Received message " . trim(substr(print_r($message, true), 6)));
            // Do something with the message
            return true;
        } catch (\Throwable $e) {
            // Handle other exceptions
            throw $e;
        } finally {
            // Always acknowledge the message
            return true;
        }
    }
}
