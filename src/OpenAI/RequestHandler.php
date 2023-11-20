<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\FatalException;

class RequestHandler
{
    public $promptBuilder;
    public $streamHandler;

    public function __construct(private Brain $brain, ?string $api_key = null, ?int $history_tokens = null)
    {
        $this->promptBuilder = new PromptBuilder($brain, $api_key, $history_tokens) or throw new FatalException("Failed to initialize PromptBuilder");
        $this->streamHandler = new StreamHandler($brain) or throw new FatalException("Failed to initialize StreamHandler");
        $this->brain->log("RequestHandler initialized.");
    }

    public function handleRequest(array $message): bool
    {
        $this->brain->log("Simulating Handling request...");
        return true;
    }
}
