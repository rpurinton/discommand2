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
            $this->sql = mysqli_connect($this->config["sql"]["host"], $myName, $myName, $myName);
            if (!$this->sql) throw new SqlException(mysqli_connect_error());
        } catch (SqlException $e) {
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

    public function __destruct()
    {
        mysqli_close($this->sql);
    }

    public function escape($string)
    {
        return mysqli_real_escape_string($this->sql, $string);
    }

    public function query($query)
    {
        $result = mysqli_query($this->sql, $query);
        if (!$result) {
            throw new SqlException(mysqli_error($this->sql));
        }
        return $result;
    }

    public function multi($query)
    {
        $result = mysqli_multi_query($this->sql, $query);
        if (!$result) {
            throw new SqlException(mysqli_error($this->sql));
        }
        return $result;
    }

    public function insert($query)
    {
        $result = mysqli_query($this->sql, $query);
        if (!$result) {
            throw new SqlException(mysqli_error($this->sql));
        }
        return mysqli_insert_id($this->sql);
    }
}
