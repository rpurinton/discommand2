<?php

namespace RPurinton\Discommand2;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand2\Modules\RabbitMQ;

class Brain extends SqlClient
{
    private LoopInterface $loop;
    private $modules = [];
    private $tokenCounter;

    public function __construct($myName)
    {
        try {
            parent::__construct($myName);
            // temporary display all errors full debugging
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
            $this->tokenCounter = new TokenCounter();
            $this->loop = Loop::get();
            $this->modules["bunny"] = new RabbitMQ($this->config["bunny"] ?? [], $this->loop, $myName, $this->inbox(...), $this->logger);
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
            $microtime = number_format(microtime(true), 6, '.', '');
            $role = "system";
            $content = $this->escape(json_encode($message));
            $tokens = $this->tokenCounter->count($content);
            $message_id = $this->insert("INSERT INTO `messages` (`microtime`, `role`, `content`, `tokens`) VALUES ('$microtime', '$role', '$content', '$tokens')");
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
