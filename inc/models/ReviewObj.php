<?php
include_once 'app/connect.php';

Class Review {
    public $review_id;
    public $user_id;
    public $restaurant_id;
    public $review_text;
    public $rating;
    public $restaurant_name;
    public $user_name;

    public function __construct($review_id, $conn, $user_name) {
      $review_sql = "SELECT * FROM Reviews WHERE review_id ='$review_id'";
      $review_query = mysqli_query($conn, $review_sql);
      $review_results = mysqli_fetch_assoc($review_query);
      $this->review_id = $review_id;
      $this->user_id = $review_results["user_id"];
      $this->restaurant_id = $review_results["restaurant_id"];
      $this->review_text = $review_results["review_text"];
      $this->rating = $review_results["rating"];

      // get the username of the review poster
      // edit 3/26 updated because it was taking too long to update profile page when obviously its the same user
      if(isset($user_name)){
        $this->user_name = $user_name;
      } else {
        $posted_by = $review_results['user_id'];
        $user_sql = "SELECT user_name FROM User WHERE user_id='$posted_by'";
        $user_result = mysqli_query($conn, $user_sql);
        $user_name = mysqli_fetch_assoc($user_result)["user_name"];
        $this->user_name = $user_name;
      }

      // get the restaurant name of the review
      $rest_sql = "SELECT restaurant_name FROM Restaurant WHERE restaurant_id ='$this->restaurant_id'";
      $rest_query = mysqli_query($conn, $rest_sql);
      $rest_results = mysqli_fetch_assoc($rest_query);
      $this->restaurant_name = $rest_results["restaurant_name"];
    }
}

?>
