<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\OpenAI\PromptBuilder;
use RPurinton\Discommand2\OpenAI\StreamHandler;
use RPurinton\Discommand2\OpenAI\FunctionLoader;
use RPurinton\Discommand2\OpenAI\FunctionHandler;
use RPurinton\Discommand2\OpenAI\TokenCounter;


class Client
{
    public $promptBuilder;
    public $streamHandler;
    public $functionLoader;
    public $functionHandler;
    public $tokenCounter;

    public function __construct()
    {
        $this->promptBuilder = new PromptBuilder();
        $this->streamHandler = new StreamHandler();
        $this->functionLoader = new FunctionLoader();
        $this->functionHandler = new FunctionHandler();
        $this->tokenCounter = new TokenCounter();
    }
}
