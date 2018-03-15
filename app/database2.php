<?php
class Database{
    // specify your own database credentials
    private $host = "cpanel3.engr.illinois.edu";
    private $db_name = "gizmohihungry_test";
    private $username = "gizmohihungry_admin";
    private $password = "password123";
    public $conn;

    // get the database connection
    public function getConnection(){

        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
