<?php

namespace RPurinton\Discommand2\Core;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand2\Core\RabbitMQ;
use RPurinton\Discommand2\OpenAI\TokenCounter;

class Brain extends SqlClient
{
    private LoopInterface $loop;
    private RabbitMQ $bunny;
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
            $this->bunny = new RabbitMQ($this->getConfig("bunny"), $this->loop, $myName, $this->inbox(...), $this->logger);
        } catch (\Throwable $e) {
            // Handle other exceptions
            throw $e;
        } finally {
            $this->log("$myName is alive.");
            return $this;
        }
    }

    public function __destruct()
    {
        $this->bunny->disconnect();
        $this->loop->stop();
        parent::__destruct();
    }

    private function inbox(array $message): bool
    {
        try {
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
            return true;
        }
    }
}
