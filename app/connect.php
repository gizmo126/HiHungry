
<?php
$mysql_hostname = "cpanel3.engr.illinois.edu";
$mysql_user = "gizmohihungry_admin";
$mysql_password = "password123";
$mysql_database = "gizmohihungry_test";

define('DBHOST', 'localhost');
define('DBUSER', 'gizmohihungry_admin');
define('DBPASS', 'password123');
define('DBNAME', 'gizmohihungry_test');

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if ( !$conn ) {
 die("Connection failed : " . mysqli_connect_errno());
 }

?>