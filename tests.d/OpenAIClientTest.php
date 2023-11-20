<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\OpenAI\Client;
use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\ConfigurationException;
use RPurinton\Discommand2\Exceptions\OpenAIException;
use RPurinton\Discommand2\OpenAI\PromptBuilder;
use RPurinton\Discommand2\OpenAI\StreamHandler;
use RPurinton\Discommand2\OpenAI\FunctionLoader;
use RPurinton\Discommand2\OpenAI\FunctionHandler;
use RPurinton\Discommand2\OpenAI\TokenCounter;


class OpenAIClientTest extends TestCase
{
    private $brain;
    private $client;

    protected function setUp(): void
    {
        $this->brain = $this->createMock(Brain::class);
        $this->brain->myName = 'testBrain';
    }

    public function testConstructWithInvalidConfigPath()
    {
        $this->expectException(ConfigurationException::class);
        $this->brain->myName = 'invalidBrain';
        $this->client = new Client($this->brain);
    }

    public function testConstructWithInvalidToken()
    {
        $this->expectException(OpenAIException::class);
        $this->brain = $this->createMock(Brain::class);
        $this->brain->myName = 'testBrain';
        $this->client = new Client($this->brain, 'invalid-token');
    }

    public function testConstructWithValidToken()
    {
        $this->brain = $this->createMock(Brain::class);
        $this->brain->myName = 'testBrain';
        $this->client = new Client($this->brain);
        $this->assertInstanceOf(Client::class, $this->client);
    }

    public function testPromptBuilderInstance()
    {
        $this->assertInstanceOf(PromptBuilder::class, $this->client->promptBuilder);
    }

    public function testStreamHandlerInstance()
    {
        $this->assertInstanceOf(StreamHandler::class, $this->client->streamHandler);
    }

    public function testFunctionLoaderInstance()
    {
        $this->assertInstanceOf(FunctionLoader::class, $this->client->functionLoader);
    }

    public function testFunctionHandlerInstance()
    {
        $this->assertInstanceOf(FunctionHandler::class, $this->client->functionHandler);
    }

    public function testTokenCounterInstance()
    {
        $this->assertInstanceOf(TokenCounter::class, $this->client->tokenCounter);
    }
}
