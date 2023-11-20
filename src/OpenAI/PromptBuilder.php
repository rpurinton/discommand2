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

    public function __construct(private Brain $brain, public ?string $api_key = null, public ?int $history_tokens = null)
    {
        $this->functionLoader = new FunctionLoader($brain);
        $this->functionHandler = new FunctionHandler($brain);
        $this->tokenCounter = new TokenCounter($brain);

        $config_file = "/home/" . $brain->myName . "/prompt.d/openai.json";
        if (!file_exists($config_file)) throw new FatalException("OpenAI configuration file not found at $config_file", true);

        $this->prompt = json_decode(file_get_contents($config_file), true);
        if (!$this->prompt) throw new FatalException("Failed to parse OpenAI configuration file at $config_file", true);

        if (!$api_key) $api_key = $this->prompt["api_key"] ?? null;
        unset($this->prompt["api_key"]);
        if (!$api_key) throw new FatalException("OpenAI API key not found in configuration file at $config_file", true);
        if (!$this->validate_api_key($api_key)) throw new FatalException("Invalid OpenAI API key", true);

        if (!$history_tokens) $history_tokens = $this->prompt["history_tokens"] ?? null;
        unset($this->prompt["history_tokens"]);
        if (!$history_tokens) throw new FatalException("OpenAI history_tokens not found in configuration file at $config_file", true);
        if (!is_int($history_tokens)) throw new FatalException("Invalid OpenAI history_tokens in $config_file", true);

        $this->brain->log("PromptBuilder initialized.");
    }

    private function validate_api_key($api_key)
    {
        return (substr($api_key, 0, 3) !== 'sk-' || strlen($api_key) !== 51);
    }
}
