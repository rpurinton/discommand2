<?php

namespace RPurinton\Discommand\Core;

use RPurinton\Discommand\Exceptions\Exception;
use RPurinton\Discommand\Exceptions\FatalException;

class SqlClient extends ConfigLoader
{
    private $sql;

    public function __construct($myName)
    {
        parent::__construct($myName);
        $this->connect();
        return true;
    }

    private function connect()
    {
        try {
            $this->sql = mysqli_connect(
                $this->config["sql"]["host"],
                $this->myName,
                $this->myName,
                $this->myName
            );
        } catch (\mysqli_sql_exception $e) {
            throw new FatalException("Failed to connect to MySQL: " . $e->getMessage());
        }

        $this->log("SqlClient connected.");
    }

    public function query($query)
    {
        $result = null;
        try {
            $this->reconnectIfNeeded();
            $result = mysqli_query($this->sql, $query);
            if (!$result) {
                throw new Exception('MySQL query error: ' . mysqli_error($this->sql));
            }
        } catch (\mysqli_sql_exception $e) {
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    public function count($result)
    {
        return mysqli_num_rows($result);
    }

    public function insert($query)
    {
        $this->query($query);
        return $this->insert_id();
    }

    public function assoc($result)
    {
        return mysqli_fetch_assoc($result);
    }

    public function escape($text)
    {
        return mysqli_real_escape_string($this->sql, $text);
    }
    public function single($query)
    {
        $this->reconnectIfNeeded();
        try {
            $result = mysqli_query($this->sql, $query) or throw new Exception('MySQL query error: ' . mysqli_error($this->sql));
        } catch (\mysqli_sql_exception $e) {
            throw new Exception($e->getMessage());
        }

        return mysqli_fetch_assoc($result);
    }

    public function multi($query): array
    {
        $this->reconnectIfNeeded();
        $result = [];
        try {
            if (!mysqli_multi_query($this->sql, $query)) {
                throw new Exception('MySQL multi-query error: ' . mysqli_error($this->sql));
            }

            do {
                if ($res = mysqli_store_result($this->sql)) {
                    $result[] = mysqli_fetch_all($res, MYSQLI_ASSOC);
                    mysqli_free_result($res);
                }
            } while (mysqli_more_results($this->sql) && mysqli_next_result($this->sql));
        } catch (\mysqli_sql_exception $e) {
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    public function insert_id()
    {
        return mysqli_insert_id($this->sql);
    }

    private function reconnectIfNeeded()
    {
        if (!mysqli_ping($this->sql)) {
            $this->connect();
        }
    }
}
