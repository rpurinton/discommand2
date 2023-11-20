<?php

namespace RPurinton\Discommand2\Core;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand2\Core\RabbitMQ;
use RPurinton\Discommand2\Core\SqlClient;
use RPurinton\Discommand2\OpenAI;
use RPurinton\Discommand2\OpenAI\TokenCounter;

class Brain extends SqlClient
{
    private ?LoopInterface $loop = null;
    private ?RabbitMQ $bunny = null;
    private ?OpenAI\Client $ai = null;
    private ?TokenCounter $tokenCounter = null;

    public function __construct($myName)
    {
        try {
            parent::__construct($myName);
            // temporary display all errors full debugging
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
            $this->loop = Loop::get();
            $this->bunny = new RabbitMQ($this->getConfig("bunny"), $this->loop, $myName, $this->inbox(...), $this->logger);
            $this->ai = new OpenAI\Client($this);
            $this->tokenCounter = new TokenCounter();
            $this->log("$myName is alive.");
        } catch (\Throwable $e) {
            // Handle other exceptions
            throw $e;
        } finally {
            return $this;
        }
    }

    public function __destruct()
    {
        if ($this->bunny) $this->bunny->disconnect();
        if ($this->loop) $this->loop->stop();
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
        } catch (\Exception $e) {
            $this->log($e->getMessage(), "ERROR");
        } finally {
            return true;
        }
    }
}
