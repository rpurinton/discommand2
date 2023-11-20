<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\Exception;
use RPurinton\Discommand2\Exceptions\FatalException;

class PromptBuilder
{
    public $functionLoader;
    public $functionHandler;
    public $tokenCounter;
    public $prompt;
    public $base_messages;

    public function __construct(private Brain $brain, public ?string $api_key = null, public ?int $history_tokens = null)
    {
        $this->functionLoader = new FunctionLoader($brain);
        $this->functionHandler = new FunctionHandler($brain);
        $this->tokenCounter = new TokenCounter($brain);

        $configLoader = new ConfigLoader("/home/" . $brain->myName . "/prompt.d");
        $this->prompt = $configLoader->loadAndValidateConfig() or throw new FatalException("Failed to load and validate OpenAI configuration");
        $this->base_messages = $configLoader->getBaseMessages() or throw new FatalException("Failed to get base messages");

        $this->brain->log("PromptBuilder initialized.");
    }
}
