<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\FatalException;

class RequestHandler
{
    public $promptBuilder;
    public $streamHandler;

    public function __construct(private Brain $brain)
    {
        $this->promptBuilder = new PromptBuilder($brain) or throw new FatalException("Failed to initialize PromptBuilder");
        $this->streamHandler = new StreamHandler($brain) or throw new FatalException("Failed to initialize StreamHandler");
        $this->brain->log("RequestHandler initialized.");
    }

    public function handleRequest(array $message): bool
    {
        $this->brain->log("Simulating Handling request...");
        $this->promptBuilder = new PromptBuilder($this->brain) or throw new FatalException("Failed to initialize PromptBuilder");
        $this->streamHandler = new StreamHandler($this->brain) or throw new FatalException("Failed to initialize StreamHandler");
        $prompt = $this->promptBuilder->prompt;
        $prompt["messages"] = $this->promptBuilder->base_messages;
        print_r($prompt);
        return true;
    }
}
