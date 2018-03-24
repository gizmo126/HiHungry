<?php
include_once 'app/connect.php';

Class Review {
    public $review_id;
    public $user_id;
    public $restaurant_id;
    public $review_text;
    public $rating;
    public $restaurant_name;

    public function __construct($review_id, $user_id, $restaurant_id, $review_text, $rating, $restaurant_name) {
      $this->review_id = $review_id;
      $this->user_id = $user_id;
      $this->restaurant_id = $restaurant_id;
      $this->review_text = $review_text;
      $this->rating = $rating;
      $this->restaurant_name = $restaurant_name;
    }
}

?>
