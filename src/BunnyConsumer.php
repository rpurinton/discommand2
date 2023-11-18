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
		parent::__construct();
		$this->client = new \Bunny\Client($this->config["bunny"]);
		$this->client->connect();
		$this->channel = $this->client->channel();
		$this->channel->qos(0, 1);
		$this->consumerTag = bin2hex(random_bytes(8));
	}

	public function __destruct()
	{
		$this->channel->cancel($this->consumerTag);
		$this->channel->queueDelete($this->queue);
		$this->channel->close();
		$this->client->disconnect();
	}

	public function run(string $queue, callable $callback): void
	{
		$this->queue = $queue;
		$this->callback = $callback;
		$this->channel->consume($this->process(...), $queue, $this->consumerTag);
	}

	private function process($message, $channel, $client)
	{
		if (($this->callback)(json_decode($message->content, true))) return $channel->ack($message);
		$channel->nack($message);
	}

	public function publish($queue, $data)
	{
		$json_string = json_encode($data);
		$this->channel->queueDeclare($queue);
		$this->channel->publish($json_string, [], '', $queue);
	}
}
