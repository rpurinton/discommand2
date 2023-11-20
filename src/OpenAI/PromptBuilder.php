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
        $this->loadAndValidateConfig($config_file);

        $this->brain->log("PromptBuilder initialized.");
    }

    private function loadAndValidateConfig(string $config_file): void
    {
        $this->loadConfig($config_file);
        $this->validateConfig($config_file);
    }

    private function loadConfig(string $config_file): void
    {
        if (!file_exists($config_file)) throw new FatalException("Configuration file not found at $config_file", true);

        $this->prompt = json_decode(file_get_contents($config_file), true);
        if (!$this->prompt) throw new FatalException("Failed to parse OpenAI configuration file at $config_file", true);
    }

    private function validateConfig(string $config_file): void
    {
        $this->validateApiKey($config_file);
        $this->validateHistoryTokens($config_file);
    }

    private function validateApiKey(string $config_file): void
    {
        if (!$this->api_key) $this->api_key = $this->prompt["api_key"] ?? null;
        unset($this->prompt["api_key"]);
        if (!$this->api_key) throw new FatalException("API key not found in configuration file at $config_file", true);
        if (!$this->validate_api_key($this->api_key)) throw new FatalException("Invalid API key", true);
    }

    private function validateHistoryTokens(string $config_file): void
    {
        if (!$this->history_tokens) $this->history_tokens = $this->prompt["history_tokens"] ?? null;
        unset($this->prompt["history_tokens"]);
        if (!$this->history_tokens) throw new FatalException("history_tokens not found in configuration file at $config_file", true);
        if (!is_int($this->history_tokens)) throw new FatalException("Invalid history_tokens in $config_file", true);
    }

    private function validate_api_key($api_key)
    {
        return (substr($api_key, 0, 3) === 'sk-' && strlen($api_key) === 51);
    }
}
