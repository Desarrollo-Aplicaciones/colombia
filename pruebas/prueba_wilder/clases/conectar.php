<?php

class Conectar {

    private $driver, $host, $user, $pass, $db, $charset;

    public function __construct() {
        $db = include '../config/db.php';
        $this->driver = $db["driver"];
        $this->host = $db["host"];
        $this->user = $db["user"];
        $this->pass = $db["pass"];
        $this->db = $db["db"];
        $this->charset = $db["charset"];
    }

    public function conexion() {
        if ($this->driver == "mysql") {
            $con = new mysqli($this->host, $this->user, $this->pass, $this->db);
            $con->query("SET NAMES '" . $this->charset . "'");
        }
        return $con;
    }

}
