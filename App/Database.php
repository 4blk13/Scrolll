<?php

class Database {
    private $db_host;
    private $db_user;
    private $db_pass;
    private $db_name;
    private $pdo;

    public function __construct() {
        $this->db_host = $_SERVER['DB_HOST'];
        $this->db_user = $_SERVER['DB_USER'];
        $this->db_pass = $_SERVER['DB_PASS'];
        $this->db_name = $_SERVER['DB_NAME'];
    }

    public function getPDO() {
        if ($this->pdo === null) {
            $this->pdo = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        }
        return $this->pdo;
    }
}

?>