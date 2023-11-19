<?php

namespace RPurinton\Discommand2;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand2\GlobalExceptionHandler;
use RPurinton\Discommand2\Exceptions\ConfigurationException;
use RPurinton\Discommand2\Exceptions\LogException;

class Brain extends SqlClient
{
    private LoopInterface $loop;
    private $bunny;

    public function __construct(public $myName)
    {
        try {
            parent::__construct($myName);
            set_exception_handler((new GlobalExceptionHandler($this->logger))->handleException(...));
            $this->loop = Loop::get();
            $this->bunny = new BunnyConsumer($this->config["bunny"] ?? [], $this->loop, $myName, $this->inbox(...));
        } catch (ConfigurationException | LogException $e) {
            // Handle exception (log or rethrow)
            throw $e;
        } catch (\Throwable $e) {
            // Handle other exceptions
            throw $e;
        } finally {
            // Always acknowledge the message
            return $this;
        }
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
