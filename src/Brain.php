<?php

namespace RPurinton\Discommand2;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand2\GlobalExceptionHandler;
use RPurinton\Discommand2\Exceptions\ConfigurationException;
use RPurinton\Discommand2\Exceptions\LogException;
use RPurinton\Discommand2\Consumers\RabbitMQ;

class Brain extends SqlClient
{
    private LoopInterface $loop;
    private $bunny;

    public function __construct(protected $myName)
    {
        try {
            parent::__construct($myName);
            set_exception_handler((new GlobalExceptionHandler($this->logger))->handleException(...));
            $this->loop = Loop::get();
            $this->bunny = new RabbitMQ($this->config["bunny"] ?? [], $this->loop, $myName, $this->inbox(...));
        } catch (ConfigurationException | LogException $e) {
            // Handle exception (log or rethrow)
            throw $e;
        } catch (\Throwable $e) {
            // Handle other exceptions
            throw $e;
        } finally {
            $this->logger->log("$myName is alive.");
            return $this;
        }
    }

    public function __destruct()
    {
        $this->loop->stop();
        parent::__destruct();
    }

    private function inbox(array $message): bool
    {
        try {
            $this->logger->log("Received message " . trim(substr(print_r($message, true), 6)));
            // Do something with the message
        } catch (LogException $e) {
            // Handle logging exception
            throw $e;
        } catch (\Throwable $e) {
            // Handle other exceptions
            throw $e;
        } finally {
            // Always acknowledge the message
            return true;
        }
    }
}
