<?php

namespace RPurinton\Discommand2;

use React\Async;
use React\EventLoop\LoopInterface;
use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;

class BunnyConsumer extends ConfigLoader
{
	private Client $client;
	private Channel $channel;
	private string $consumerTag;

	public function __construct(LoopInterface $loop, private string $queue, private callable $callback)
	{
		echo ("Consumer Construct\n");
		parent::__construct();
		$this->consumerTag = bin2hex(random_bytes(8));
		$this->client = new Client($loop, $this->config["bunny"]);
		$this->client->connect()->then($this->getChannel(...))->then($this->consume(...));
	}

	public function __destruct()
	{
		echo ("Consumer Destruct\n");
		if ($this->channel) {
			$this->channel->cancel($this->consumerTag);
			$this->channel->queueDelete($this->queue);
			$this->channel->close();
		}
		if ($this->client) $this->client->disconnect();
		parent::__destruct();
	}

	private function getChannel(Client $client)
	{
		return $client->channel();
	}

	private function consume(Channel $channel)
	{
		$this->channel = $channel;
		$channel->qos(0, 1);
		$this->channel->queueDeclare($this->queue);
		$channel->consume($this->process(...), $this->queue);
	}

	private function process(Message $message, Channel $channel, Client $client)
	{
		echo ("Consumer Process\n");
		if (($this->callback)(json_decode($message->content, true))) return $channel->ack($message);
		$channel->nack($message);
	}

	public function publish(string $queue, array $data): bool
	{
		echo ("Consumer Publish\n");
		if (!$this->channel) return false;
		$this->channel->queueDeclare($queue);
		return Async\await($this->channel->publish(json_encode($data), [], '', $queue));
	}
}
