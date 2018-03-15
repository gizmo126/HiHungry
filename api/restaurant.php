<?php
include("../app/database.php");
$db = new Database();
$connection = $db->getConnstring();

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method)
{
  case 'GET':
    // Retrive Products
    if(!empty($_GET["id"]))
    {
      $id=intval($_GET["id"]);
      get_restaurants($id);
    } else {
      get_restaurants();
    }
    break;
  default:
    // Invalid Request Method
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}

function get_restaurants()
{
   global $connection;
   $query = "SELECT * FROM Restaurant";
   $response = array();
   $result = mysqli_query($connection, $query);
   while($row = mysqli_fetch_array($result))
   {
     $response[]=$row;
   }
   header('Content-Type: application/json');
   echo json_encode($response);
}


?>
