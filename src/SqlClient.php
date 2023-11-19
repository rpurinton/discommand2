<?php

namespace RPurinton\Discommand2;

use RPurinton\Discommand2\Exceptions\SqlException;

class SqlClient extends ConfigLoader
{
    private $sql;

    public function __construct(protected $myName)
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
                die("Failed to connect to MySQL: " . mysqli_connect_error());
            }
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
