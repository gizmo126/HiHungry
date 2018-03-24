<?php
include_once 'app/connect.php';

Class Restaurant {
    public $restaurant_id;
    public $restaurant_name;
    public $address;
    public $city;
    public $zipcode;
    public $pricerange;
    public $delivers;
    public $rating;
    public $votes;
    public $cuisine_ids;
    public $cuisine_names;

    public function __construct($restaurant_id, $conn) {
        $rest_sql = "SELECT * FROM Restaurant WHERE restaurant_id ='$restaurant_id'";
        $rest_query = mysqli_query($conn, $rest_sql);
        $rest_results = mysqli_fetch_assoc($rest_query);
        $this->restaurant_id = $rest_results["restaurant_id"];
        $this->restaurant_name = $rest_results["restaurant_name"];
        $this->address = $rest_results["address"];
        $this->city = $rest_results["city"];
        $this->zipcode = $rest_results["zipcode"];
        $this->pricerange = $rest_results["price_range"];
        $this->delivers = $rest_results["delivers"];
        $this->rating = $rest_results["rating"];
        $this->votes = $rest_results["votes"];

        // get cuisine id
        $this->cuisine_ids = [];
        $cuisine_sql = "SELECT cuisine_id FROM Restaurant_Type WHERE restaurant_id ='$restaurant_id'";
        $cuisine_query = mysqli_query($conn, $cuisine_sql);
        foreach(mysqli_fetch_assoc($cuisine_query) as &$row) {
          array_push($this->cuisine_ids, $row);
        }

        // get cuisine strings from cuisine ids
        $this->cuisine_names = [];
        foreach($this->cuisine_ids as &$id){
          $cuisine_name_sql = "SELECT cuisine_name FROM Cuisine WHERE cuisine_id ='$id'";
          $cuisine_name_query = mysqli_query($conn, $cuisine_name_sql);
          foreach(mysqli_fetch_assoc($cuisine_name_query) as &$row) {
            array_push($this->cuisine_names, $row);
          }
        }
    }
}

?>
