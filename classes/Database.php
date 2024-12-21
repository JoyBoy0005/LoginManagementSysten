<?php

class Database
{
    private $conn;

    public function __construct()
    {
        include_once '../connect.php';
        $this->conn = $conn;
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("SQL error: " . $this->conn->error);
        }

        if ($params) {
            $types = str_repeat('s', count($params)); // Assuming all params are strings
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            die("Execution failed: " . $stmt->error);
        }

        return $stmt;
    }

    public function fetch($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        if (!$result) {
            die("Fetch error: " . $this->conn->error);
        }
        return $result->fetch_assoc();
    }

    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        if (!$result) {
            die("FetchAll error: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function close()
    {
        $this->conn->close();
    }
}
?>
