<?php

namespace RPurinton\Discommand2;

class BunnyConsumer extends ConfigLoader
{
	private $client;
	private $channel;
	private $callback;

	public function __construct(private $queue)
	{
		parent::__construct();
		$this->client = new \Bunny\Client($this->config["bunny"]);
		$this->client->connect();
		$this->channel = $this->client->channel();
	}

	public function __destruct()
	{
		$this->channel->close();
		$this->client->disconnect();
	}

	public function run(callable $callback): void
	{
		$this->callback = $callback;
		$this->channel->consume($this->process(...), $this->queue);
	}

	private function process($message, $channel, $client)
	{
		if (($this->callback)(json_decode($message->content, true))) {
			$channel->ack($message);
		} else {
			$channel->nack($message);
		}
	}

	public function publish($queue, $data)
	{
		$json_string = json_encode($data);
		$this->channel->queueDeclare($queue);
		$this->channel->publish($json_string, [], '', $queue);
	}
}
