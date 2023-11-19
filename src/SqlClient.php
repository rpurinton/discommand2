<?php

namespace RPurinton\Discommand2;

use RPurinton\Discommand2\Exceptions\SqlException;

class SqlClient extends ConfigLoader
{
    private $sql;

    public function __construct($myName)
    {
        parent::__construct($myName);
        if (!$this->connect()) {
            throw new SqlException("Failed to connect to MySQL.");
        }
    }

    public function __destruct()
    {
        if ($this->sql) mysqli_close($this->sql);
    }

    private function connect(): bool
    {
        $this->sql = mysqli_connect($this->config["sql"]["host"], $this->myName, $this->myName, $this->myName);
        if (!$this->sql) {
            $this->logger->log("Failed to connect to MySQL: " . mysqli_connect_error(), "ERROR");
            return false;
        }
        $this->logger->log("Connected to MySQL.");
        return true;
    }
    public function query($query)
    {
        if (!mysqli_ping($this->sql)) $this->connect();
        return mysqli_query($this->sql, $query);
    }

    public function count($result)
    {
        return mysqli_num_rows($result);
    }

    public function insert($query)
    {
        $result = $this->query($query);
        if (!$result) {
            throw new \Exception('MySQL insert error: ' . \mysqli_error($this->sql));
        }
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
        if (!mysqli_ping($this->sql)) $this->connect();
        return mysqli_fetch_assoc(mysqli_query($this->sql, $query));
    }

    public function multi($query): array
    {
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
    }

    public function insert_id()
    {
        return \mysqli_insert_id($this->sql);
    }

    public function query_function($query, $db_name = null)
    {
        try {
            // select the database to query
            if (!empty($db_name)) {
                \mysqli_select_db($this->sql, $db_name);
            }
            $result = $this->multi($query);
            if (!count($result)) {
                throw new \Exception('MySQL query error: ' . \mysqli_error($this->sql));
            }
            // return an array with success true and a string of the results in csv format with headers and finally a total number of rows returned
            $csv = "";
            foreach ($result as $key => $value) {
                $csv .= implode(",", array_keys($value[0])) . "\n";
                foreach ($value as $key2 => $value2) {
                    $csv .= implode(",", $value2) . "\n";
                }
            }
            return ["success" => true, "csv" => $csv];
        } catch (\Exception $e) {
            return ["success" => false, "error" => $e->getMessage()];
        } catch (\Error $e) {
            return ["success" => false, "error" => $e->getMessage()];
        } catch (\Throwable $e) {
            return ["success" => false, "error" => $e->getMessage()];
        } finally {
            // select the original database
            \mysqli_select_db($this->sql, $this->config["sql"]["db"]);
        }
    }
}
