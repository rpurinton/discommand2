<?php

namespace RPurinton\Discommand2\Core;

use RPurinton\Discommand2\Exceptions\SqlException;

class SqlClient extends ConfigLoader
{
    private $sql;

    public function __construct($myName)
    {
        parent::__construct($myName);
        $this->connect();
    }

    public function __destruct()
    {
        if ($this->sql) {
            mysqli_close($this->sql);
        }
    }

    private function connect()
    {
        $this->sql = mysqli_connect(
            $this->config["sql"]["host"],
            $this->myName,
            $this->myName,
            $this->myName
        );

        if (!$this->sql) {
            $this->log("Failed to connect to MySQL: " . mysqli_connect_error(), "ERROR");
            throw new SqlException("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        $this->log("SqlClient connected");
    }

    public function query($query)
    {
        $result = null;
        try {
            $this->reconnectIfNeeded();
            $result = mysqli_query($this->sql, $query);
            if (!$result) {
                throw new SqlException('MySQL query error: ' . mysqli_error($this->sql));
            }
        } catch (\Exception $e) {
            throw new SqlException($e->getMessage());
        } catch (\Throwable $e) {
            throw new SqlException($e->getMessage());
        } catch (\Error $e) {
            throw new SqlException($e->getMessage());
        } finally {
            return $result;
        }
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
        $result = mysqli_query($this->sql, $query);

        if (!$result) {
            throw new SqlException('MySQL query error: ' . mysqli_error($this->sql));
        }

        return mysqli_fetch_assoc($result);
    }

    public function multi($query): array
    {
        $this->reconnectIfNeeded();
        if (!mysqli_multi_query($this->sql, $query)) {
            throw new SqlException('MySQL multi-query error: ' . mysqli_error($this->sql));
        }

        $result = [];
        do {
            if ($res = mysqli_store_result($this->sql)) {
                $result[] = mysqli_fetch_all($res, MYSQLI_ASSOC);
                mysqli_free_result($res);
            }
        } while (mysqli_more_results($this->sql) && mysqli_next_result($this->sql));

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
