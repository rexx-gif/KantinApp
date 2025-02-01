<?php

require_once 'include/database.php';

class Minuman {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }


    public function getMinuman() {
        $query = "SELECT * FROM minuman";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>