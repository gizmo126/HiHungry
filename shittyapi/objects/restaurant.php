
<?php
class Restaurant{
  // https://www.codeofaninja.com/2017/02/create-simple-rest-api-in-php.html

    // database connection and table name
    private $conn;
    private $table_name = "Restaurant";

    // object properties
    public $restaurant_id;
    public $restaurant_name;
    public $address;
    public $city;
    public $zipcode;
    public $price_range;
    public $delivers;
    public $rating;
    public $votes;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
        // select all query
        $query = "SELECT
                    r.restaurant_id, r.restaurant_name, r.address, r.city, r.zipcode, r.price_range, r.delivers, r.rating, r.votes
                  FROM
                    " . $this->table_name . " r";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }
}
