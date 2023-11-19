<?php

namespace RPurinton\Discommand2;

use RPurinton\Discommand2\Exceptions\SqlException;

class SqlClient extends ConfigLoader
{
    private $sql;

    public function __construct($myName)
    {
        try {
            parent::__construct($myName);
            $this->connect();
        } catch (SqlException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            return $this;
        }
    }

    public function __destruct()
    {
        if ($this->sql) mysqli_close($this->sql);
    }

    private function connect(): void
    {
        try {
            $this->sql = mysqli_connect($this->config["sql"]["host"], $this->myName, $this->myName, $this->myName);
            if (!$this->sql) {
                $this->logger->log("Failed to connect to MySQL: " . mysqli_connect_error(), "ERROR");
                die();
            }
            mysqli_set_charset($this->sql, "utf8mb4");
            mysqli_query($this->sql, "SET NAMES 'utf8mb4'");
            mysqli_query($this->sql, "SET CHARACTER SET utf8mb4");
            mysqli_query($this->sql, "SET CHARACTER_SET_CONNECTION=utf8mb4");
            mysqli_query($this->sql, "SET SQL_MODE = ''");
            mysqli_query($this->sql, "SET time_zone = '+00:00'");
            mysqli_query($this->sql, "SET @@session.sql_mode = 'NO_ENGINE_SUBSTITUTION'");
            mysqli_query($this->sql, "SET @@session.innodb_strict_mode = 1");
            mysqli_query($this->sql, "SET @@session.innodb_flush_log_at_trx_commit = 1");
            mysqli_query($this->sql, "SET @@session.innodb_file_per_table = 1");
            mysqli_query($this->sql, "SET @@session.innodb_large_prefix = 1");
            mysqli_query($this->sql, "SET @@session.innodb_buffer_pool_size = 134217728");
            mysqli_query($this->sql, "SET @@session.innodb_log_file_size = 134217728");
            mysqli_query($this->sql, "SET @@session.innodb_flush_method = O_DIRECT");
            mysqli_query($this->sql, "SET @@session.innodb_log_buffer_size = 8388608");
            mysqli_query($this->sql, "SET @@session.innodb_lock_wait_timeout = 50");
            mysqli_query($this->sql, "SET @@session.innodb_rollback_on_timeout = 1");
            mysqli_query($this->sql, "SET @@session.innodb_print_all_deadlocks = 1");
            mysqli_query($this->sql, "SET @@session.innodb_autoinc_lock_mode = 2");
            mysqli_query($this->sql, "SET @@session.innodb_stats_on_metadata = 0");
            mysqli_query($this->sql, "SET @@session.innodb_strict_mode = 1");
            mysqli_query($this->sql, "SET @@session.innodb_file_format = 'Barracuda'");
            mysqli_query($this->sql, "SET @@session.innodb_file_format_max = 'Barracuda'");
            mysqli_query($this->sql, "SET @@session.innodb_buffer_pool_instances = 1");
            mysqli_query($this->sql, "SET @@session.innodb_buffer_pool_chunk_size = 134217728");
            $this->logger->log("Connected to MySQL.");
        } catch (SqlException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function escape($string): ?string
    {
        try {
            return mysqli_real_escape_string($this->sql, $string);
        } catch (SqlException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            return null;
        }
    }

    public function query($query): ?\mysqli_result
    {
        try {
            if (!mysqli_ping($this->sql)) $this->connect();
            $result = mysqli_query($this->sql, $query);
            if (!$result) {
                throw new SqlException(mysqli_error($this->sql));
            }
            return $result;
        } catch (SqlException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            return null;
        }
    }

    public function multi($query): ?array
    {
        try {
            if (!mysqli_ping($this->sql)) $this->connect();
            mysqli_multi_query($this->sql, $query);
            $result = [];
            do {
                if ($res = mysqli_store_result($this->sql)) {
                    $result[] = mysqli_fetch_all($res, MYSQLI_ASSOC);
                    mysqli_free_result($res);
                }
            } while (mysqli_more_results($this->sql) && mysqli_next_result($this->sql));
            return $result;
        } catch (SqlException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            return null;
        }
    }

    public function insert($query): int|string|null
    {
        try {
            if (!mysqli_ping($this->sql)) $this->connect();
            $result = $this->query($query);
            if (!$result) {
                throw new SqlException(mysqli_error($this->sql));
            }
            return mysqli_insert_id($this->sql);
        } catch (SqlException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            return null;
        }
    }
}
