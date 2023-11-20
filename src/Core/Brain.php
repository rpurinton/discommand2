<?php

namespace RPurinton\Discommand2\Core;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand2\Core\RabbitMQ;
use RPurinton\Discommand2\Core\SqlClient;
use RPurinton\Discommand2\OpenAI;
use RPurinton\Discommand2\OpenAI\TokenCounter;
use RPurinton\Discommand2\Exceptions\Exception;
use RPurinton\Discommand2\Exceptions\FatalException;

class Brain extends SqlClient
{
    private ?LoopInterface $loop = null;
    private ?RabbitMQ $bunny = null;
    private ?OpenAI\Client $ai = null;
    private ?TokenCounter $tokenCounter = null;

    public function __construct(string $myName)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        parent::__construct($myName) or throw new FatalException("Failed to initialize SQL client");
        $this->loop = Loop::get() or throw new FatalException("Failed to initialize event loop");
        $this->bunny = new RabbitMQ($this->getConfig("bunny"), $this->loop, $myName, $this->inbox(...), $this) or throw new FatalException("Failed to initialize RabbitMQ");
        $this->ai = new OpenAI\Client($this) or throw new FatalException("Failed to initialize OpenAI client");
        $this->tokenCounter = new TokenCounter() or throw new FatalException("Failed to initialize TokenCounter");
        $this->alive = true;
        $this->log("$myName is alive.") or throw new FatalException("Failed to log");
        return $this;
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
            $content = $this->escape(json_encode($message)) or throw new Exception("Failed to escape message content");
            $tokens = $this->tokenCounter->count($content) or throw new Exception("Failed to count tokens");
            $message_id = $this->insert("INSERT INTO `messages` (`microtime`, `role`, `content`, `tokens`) VALUES ('$microtime', '$role', '$content', '$tokens')") or throw new Exception("Failed to insert message");
            // Do something with the message
        } catch (\Exception $e) {
            $this->log($e->getMessage(), "ERROR") or throw new FatalException("Failed to log");
        } finally {
            return true;
        }
    }
}
