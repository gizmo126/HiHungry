<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../app/database.php';
include_once '../objects/restaurant.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$restaurant = new Restaurant($db);

// query products
$stmt = $restaurant->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
    // restaurants array
    $restaurants_arr=array();
    $restaurants_arr["records"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        $restaurant_item=array(
            "restaurant_id" => $restaurant_id,
            "restaurant_name" => $restaurant_name,
            "address" => $address,
            "city" => $city,
            "zipcode" => $zip_code,
            "price_range" => $price_range,
            "delivers" => $delivers,
            "rating" => $rating,
            "votes" => $votes
        );
        array_push($restaurants_arr["records"], $restaurant_item);
    }
    echo json_encode($restaurants_arr);
}
else{
    echo json_encode(
        array("message" => "No restaurants found.")
    );
}
?>
