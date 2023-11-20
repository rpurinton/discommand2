<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\Exception;
use RPurinton\Discommand2\Exceptions\FatalException;

class RequestHandler
{
    public $promptBuilder;
    public $streamHandler;

    public function __construct(private Brain $brain)
    {
        $promptBuilder = new PromptBuilder($brain);
        $streamHandler = new StreamHandler($brain);
        $this->brain->log("RequestHandler initialized.");
    }

    public function handleRequest(array $message): bool
    {
        $this->brain->log("Simulating Handling request...");
        return true;
    }
}
