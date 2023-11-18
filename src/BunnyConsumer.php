<?php

namespace RPurinton\Discommand2;

use React\Async;
use React\EventLoop\LoopInterface;
use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
use RPurinton\Discommand2\Exceptions\MessageQueueException;

class BunnyConsumer extends ConfigLoader
{
	private Client $client;
	private Channel $channel;
	private string $consumerTag;

	public function __construct(LoopInterface $loop, private string $queue, private $callback)
	{
		parent::__construct();
		try {
			$this->consumerTag = bin2hex(random_bytes(8));
			$this->client = new Client($loop, $this->config["bunny"]);
			$this->client->connect()->then($this->getChannel(...))->then($this->consume(...));
		} catch (\Throwable $e) {
			throw new MessageQueueException('Failed to initialize BunnyConsumer', 0, $e);
		}
	}

	public function __destruct()
	{
		if ($this->channel) {
			$this->channel->cancel($this->consumerTag);
			$this->channel->queueDelete($this->queue);
			$this->channel->close();
		}
		if ($this->client) $this->client->disconnect();
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
		try {
			if (($this->callback)(json_decode($message->content, true))) {
				return $channel->ack($message);
			}
			$channel->nack($message);
		} catch (\Throwable $e) {
			throw new MessageQueueException('Failed to process message', 0, $e);
		}
	}

	public function publish(string $queue, array $data): bool
	{
		try {
			if (!$this->channel) throw new MessageQueueException('Channel not initialized');
			$this->channel->queueDeclare($queue);
			return Async\await($this->channel->publish(json_encode($data), [], '', $queue));
		} catch (\Throwable $e) {
			throw new MessageQueueException('Failed to publish message', 0, $e);
		}
	}
}
