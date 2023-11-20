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
            if (!is_dir("/home/$myName")) die("$myName has not been created. Please run 'newBrain.php $myName' first.\n");
            $this->logger = new Logger("/home/$myName/logs.d");
            foreach (glob(__DIR__ . "/../conf.d/*.json") as $configFile) $this->config[basename($configFile, '.json')] = json_decode(file_get_contents($configFile), true);
        } catch (ConfigurationException $e) {
            throw new ConfigurationException("Failed to load configuration: {$e->getMessage()}");
        } catch (LogException $e) {
            $log_message = escapeshellarg($e->getMessage());
            exec("echo $log_message | systemd-cat -p error -t discommand2");
            throw new LogException("Failed to initialize logger: {$e->getMessage()}");
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            $this->logger->log("ConfigLoader initialized");
            return $this;
        }
    }

    // Intentionally trigger a ConfigurationException
    public function loadInvalidConfig()
    {
        throw new ConfigurationException("Intentional exception for testing purposes.");
    }
}
