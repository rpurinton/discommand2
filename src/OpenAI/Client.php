<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\OpenAI\PromptBuilder;
use RPurinton\Discommand2\OpenAI\StreamHandler;
use RPurinton\Discommand2\OpenAI\FunctionLoader;
use RPurinton\Discommand2\OpenAI\FunctionHandler;
use RPurinton\Discommand2\OpenAI\TokenCounter;
use RPurinton\Discommand2\Core\Brain;
use Rpurinton\Discommand2\Exceptions\OpenAIException;


class Client
{
    public $promptBuilder;
    public $streamHandler;
    public $functionLoader;
    public $functionHandler;
    public $tokenCounter;
    private $client;
    private $prompt;

    public function __construct(private Brain $brain)
    {
        $this->promptBuilder = new PromptBuilder();
        $this->streamHandler = new StreamHandler();
        $this->functionLoader = new FunctionLoader();
        $this->functionHandler = new FunctionHandler();
        $this->tokenCounter = new TokenCounter();
        $config_file = "/home/" . $brain->myName . "/prompt.d/openai.json";
        if (!file_exists($config_file)) throw new OpenAIException("OpenAI configuration file not found.");
        $this->prompt = json_decode(file_get_contents($config_file), true);
        $token = $this->prompt["token"];
        unset($this->prompt["token"]);
        $this->client = \OpenAI::client($token);
        $brain->log("OpenAI initialized");
    }
}
