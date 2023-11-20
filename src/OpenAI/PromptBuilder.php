<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\Exception;
use RPurinton\Discommand2\Exceptions\FatalException;

class PromptBuilder
{
    public ?ConfigLoader $config = null;
    public ?FunctionLoader $functionLoader = null;
    public ?FunctionHandler $functionHandler = null;
    public ?TokenCounter $tokenCounter = null;
    public ?array $prompt = null;
    public ?array $base_messages = null;

    public function __construct(private Brain $brain, public ?string $api_key = null, public ?int $history_tokens = null)
    {
        $this->functionLoader = new FunctionLoader($brain);
        $this->functionHandler = new FunctionHandler($brain);
        $this->tokenCounter = new TokenCounter($brain);
        $this->config = new ConfigLoader("/home/" . $brain->myName . "/prompt.d");

        $this->prompt = $this->config->loadAndValidateConfig() or throw new FatalException("Failed to load and validate OpenAI configuration");
        $this->base_messages = $this->config->getBaseMessages() or throw new FatalException("Failed to get base messages");

        $this->brain->log("PromptBuilder initialized.");
    }
}
