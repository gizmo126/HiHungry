<?php
  @ob_start();
  session_start();
  include 'inc/header.php';
  include 'inc/footer.php';
  include 'app/connect.php';
  include 'inc/models/ReviewObj.php';
  include 'inc/models/RestaurantObj.php';

  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    if(isset($_GET['restid'])) {
      $user = $_SESSION['user'];
      $user_sql = "SELECT user_id FROM User WHERE user_name='$user'";
      $user_result = mysqli_query($conn, $user_sql);
      $user_id = mysqli_fetch_assoc($user_result)["user_id"];

      // Get passed data
      $restid = $_GET['restid'];
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

      // Execute the python script with the JSON data
      $result = shell_exec('/usr/bin/env python analysis.py ' . escapeshellarg(json_encode($reviews_text)));
      $resultData = json_decode($result, true);
      echo $resultData;
    } else {
      header("location:javascript://history.go(-1)");
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
    <?php
      if(!empty($rest->img_url)){
        $imageData = base64_encode(file_get_contents($rest->img_url));
        echo '<img src="data:image/jpeg;base64,'. $imageData .'" class="img-thumbnail" style="width:50%">';
      } else {
        echo '<img src="https://images.pexels.com/photos/262978/pexels-photo-262978.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" class="img-thumbnail" style="width:25%">';
      }
    ?>
    <div class="top-buffer"></div>
    <div class="text-center">
        <div class="row" id="stars">
          <?php $counter = 0;
                while($counter < $rest->rating){
                    echo '<span class="fa fa-star checked"></span>';
                    $counter++;
                }
                while($counter < 5){
                    echo '<span class="fa fa-star"></span>';
                    $counter++;
                }
          ?>
        </div>
        <p><?php if(isset($rest->rating)){ echo $rest->rating; } ?> rating based on <?php if(isset($rest->votes)){ echo $rest->votes . " Votes"; } ?>.</p>
        <div class="top-buffer"></div>
        <div class="row">
          <?php
            if($resultData > 0){
              echo '<h3>Sentiment:</h3> <div style="font-size:18px; color:#006600; margin-top:10px">Positive</div>';
            } else {
              echo '<h3>Sentiment:</h3> <div style="font-size:18px; color:#cc0000; margin-top:10px">Negative</div>';
            }
          ?>
        </div>
        <div class="top-buffer"></div>
        <div class="row" style="margin-top:100px"><img src="img/word_cloud.png" style="width:50%"></div>
        <div class="row" style="margin-top:100px"><img src="img/classif_report.png" style="width:50%"></div>
    </div>
  </div>
