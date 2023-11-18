<?php

namespace RPurinton\Discommand2;

class BunnyConsumer extends ConfigLoader
{
	private $queue;
	private $client;
	private $channel;
	private $callback;
	private $consumerTag;

	public function __construct()
	{
		echo("Consumer Construct\n");
		parent::__construct();
		$this->client = new \Bunny\Client($this->config["bunny"]);
		$this->client->connect();
		$this->channel = $this->client->channel();
		$this->channel->qos(0, 1);
		$this->consumerTag = bin2hex(random_bytes(8));
	}

	public function __destruct()
	{
		echo("Consumer Destruct\n");
		$this->channel->cancel($this->consumerTag);
		$this->channel->queueDelete($this->queue);
		$this->channel->close();
		$this->client->disconnect();
		parent::__destruct();
	}

	public function run(string $queue, callable $callback): void
	{
		echo("Consumer Run\n");
		$this->queue = $queue;
		$this->callback = $callback;
		$this->channel->consume($this->process(...), $queue, $this->consumerTag);
	}

	private function process($message, $channel, $client)
	{
		echo("Consumer Process\n");
		if (($this->callback)(json_decode($message->content, true))) return $channel->ack($message);
		$channel->nack($message);
	}

	public function publish($queue, $data)
	{
		echo("Consumer Publish\n");
		$json_string = json_encode($data);
		$this->channel->queueDeclare($queue);
		$this->channel->publish($json_string, [], '', $queue);
	}
}
