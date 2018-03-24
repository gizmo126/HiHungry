
<?php
define('DBHOST', 'cpanel3.engr.illinois.edu'); // cpanel3.engr.illinois.edu
define('DBUSER', 'gizmohihungry_admin'); // gizmohihungry_admin
define('DBPASS', 'password123'); // password123
define('DBNAME', 'gizmohihungry_test'); // gizmohihungry_test
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
/*
$DBHOST="cpanel3.engr.illinois.edu"; // cpanel3.engr.illinois.edu
$DBUSER="gizmohihungry_admin"; // gizmohihungry_admin
$DBPASS="password123"; // password123
$DBNAME="gizmohihungry_test";
*/

//$conn = mysqli_connect($DBHOST, $DBUSER, $DBPASS, $DBNAME);
if ( !$conn ) {
 die("Connection failed : " . mysqli_connect_errno());
}

?>
