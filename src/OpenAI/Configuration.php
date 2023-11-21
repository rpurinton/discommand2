<?php

namespace RPurinton\Discommand\OpenAI;

use RPurinton\Discommand\Exceptions\FatalException;

class Configuration
{
    public ?string $api_key = null;
    public ?int $history_tokens = null;
    public array $base_prompt = [];

    public function __construct(public string $myName)
    {
    }

    public function getBasePrompt(): array
    {
        $files = glob($this->prompt_dir . "/*.json");
        $files = array_merge($files, glob($this->prompt_dir . "/*.txt"));
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== "openai.json") {
                $messages[] = ["role" => "system", "content" => "[" . basename($file) . "]\n\n" . file_get_contents($file) . "\n\n"];
            }
        }
        return $messages;
    }

    public function loadAndValidateConfig(): array
    {
        $this->loadConfig($this->prompt_dir . "/openai.json");
        $this->validateConfig($this->prompt_dir . "/openai.json");
        return $this->prompt;
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
        if (!$this->api_key) throw new FatalException("API key not found in configuration file at $config_file", true);
        if (!$this->validate_api_key($this->api_key)) throw new FatalException("Invalid API key", true);
    }

    private function validateHistoryTokens(string $config_file): void
    {
        if (!$this->history_tokens) $this->history_tokens = $this->prompt["history_tokens"] ?? null;
        if (!$this->history_tokens) throw new FatalException("history_tokens not found in configuration file at $config_file", true);
        if (!is_int($this->history_tokens)) throw new FatalException("Invalid history_tokens in $config_file", true);
    }

    private function validate_api_key($api_key)
    {
        return (substr($api_key, 0, 3) === 'sk-' && strlen($api_key) === 51);
    }
}
