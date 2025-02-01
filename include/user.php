<?php
class User {
    private $conn;
    private $table_name = 'user';


    public $id;
    public $username;
    public $password;
    public $email;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }
}
?>