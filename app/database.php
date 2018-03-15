<?php
class Database{
    // specify your own database credentials
    private $host = "cpanel3.engr.illinois.edu";
    private $db_name = "gizmohihungry_test";
    private $username = "gizmohihungry_admin";
    private $password = "password123";
    var $conn;

    // get the database connection
  	function getConnstring() {
  		$con = mysqli_connect($this->host, $this->username, $this->password, $this->db_name) or die("Connection failed: " . mysqli_connect_error());

  		/* check connection */
  		if (mysqli_connect_errno()) {
  			printf("Connect failed: %s\n", mysqli_connect_error());
  			exit();
  		} else {
  			$this->conn = $con;
  		}
  		return $this->conn;
  	}
}
?>
