<?php

namespace RPurinton\Discommand2\Core;

use RPurinton\Discommand2\Exceptions\ConfigurationException;
use RPurinton\Discommand2\Exceptions\LogException;

class ConfigLoader
{
    protected $config = [];
    protected $logger;
    protected $exceptionHandler;

    public function __construct(protected $myName)
    {
        try {
            $this->exceptionHandler = new GlobalExceptionHandler($this->logger);
            if (!is_dir("/home/$myName")) throw new ConfigurationException("$myName has not been created. Please run 'newBrain.php $myName' first.");
            $this->logger = new Logger("/home/$myName/logs.d");
            foreach (glob(__DIR__ . "/../../conf.d/*.json") as $configFile) $this->config[basename($configFile, '.json')] = json_decode(file_get_contents($configFile), true);
        } catch (ConfigurationException $e) {
            throw new ConfigurationException("Failed to load configuration: {$e->getMessage()}");
        } catch (LogException $e) {
            echo ("Failed to initialize logger: {$e->getMessage()}\n");
            $log_message = escapeshellarg($e->getMessage());
            exec("echo $log_message | systemd-cat -p error -t discommand2");
            throw new LogException("Failed to initialize logger: {$e->getMessage()}");
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            $this->log("ConfigLoader initialized");
            return $this;
        }
    }

    public function getConfig(string $section): array
    {
        return $this->config[$section] ?? [];
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }

    public function log(string $message, string $level = 'INFO'): void
    {
        $this->logger->log($message, $level);
    }

    // Intentionally trigger a ConfigurationException
    public function loadInvalidConfig()
    {
        throw new ConfigurationException("Intentional exception for testing purposes.");
    }
}
