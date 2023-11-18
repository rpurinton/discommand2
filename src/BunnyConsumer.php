<?php

namespace RPurinton\Discommand2;

use RPurinton\Discommand2\ConfigLoader;
use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
use React\EventLoop\Loop;

class BunnyConsumer extends ConfigLoader
{
	private $loop;
	private $queue;
	private $client;
	private $channel;
	private $callback;
	private $consumerTag;

	public function __construct()
	{
		echo ("Consumer Construct\n");
		parent::__construct();
		$this->loop = Loop::get();
		$this->client = new Client($this->loop, $this->config["bunny"]);
		$this->client->connect();
		$this->channel = $this->client->channel();
		$this->channel->qos(0, 1);
		$this->consumerTag = bin2hex(random_bytes(8));
	}

	public function __destruct()
	{
		echo ("Consumer Destruct\n");
		$this->channel->cancel($this->consumerTag);
		$this->channel->queueDelete($this->queue);
		$this->channel->close();
		$this->client->disconnect();
		parent::__destruct();
	}

	public function run(string $queue, callable $callback): void
	{
		echo ("Consumer Run\n");
		$this->queue = $queue;
		$this->callback = $callback;
		$this->channel->consume($this->process(...), $queue, $this->consumerTag);
	}

	private function process(Message $message, Channel $channel, Client $client)
	{
		echo ("Consumer Process\n");
		if (($this->callback)(json_decode($message->content, true))) return $channel->ack($message);
		$channel->nack($message);
	}

	public function publish($queue, $data)
	{
		echo ("Consumer Publish\n");
		$json_string = json_encode($data);
		$this->channel->queueDeclare($queue);
		$this->channel->publish($json_string, [], '', $queue);
	}
}
