<?php

require_once 'include/database.php';

class Makanan {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }


    public function getMakanan() {
        $query = "SELECT * FROM makanan";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>