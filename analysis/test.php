<?php
  @ob_start();
  session_start();
  include 'inc/header.php';
  include 'inc/footer.php';
  include 'app/connect.php';
  include 'inc/models/ReviewObj.php';
  include 'inc/models/RestaurantObj.php';

  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
      $user = $_SESSION['user'];
      $user_sql = "SELECT user_id FROM User WHERE user_name='$user'";
      $user_result = mysqli_query($conn, $user_sql);
      $user_id = mysqli_fetch_assoc($user_result)["user_id"];

      // Get passed data
      $restid = '16930344';
      $rest = new Restaurant($restid, $conn);

      // Get Review
      $reviews_sql = "SELECT * FROM Reviews WHERE restaurant_id='$restid'";
      $reviews_result = mysqli_query($conn, $reviews_sql);
      $reviews = [];
      $reviews_text = [];
      if(mysqli_num_rows($reviews_result) > 0){
          while($row = mysqli_fetch_assoc($reviews_result)){
              $review = new Review($row['review_id'], $conn, NULL);
              array_push($reviews, $review);
              array_push($reviews_text, $review->review_text);
          }
      }
      echo count($reviews_text);
      // Execute the python script with the JSON data
      $result = shell_exec('/usr/bin/env python test.py ' . escapeshellarg(json_encode($reviews_text)));
      $resultData = json_decode($result, true);
      echo $result;
      foreach($resultData as &$r){
          echo $r . '<br>';
      }
  } else {
      header('Location: login.php');
  }
?>
<style>
  <?php include 'css/restaurant.css'; ?>
</style>
<div class="container">
  <div class="starter-template">
        <h1><?php echo $rest->restaurant_name . ' Review Data'; ?></h1>
  </div>
</div>
