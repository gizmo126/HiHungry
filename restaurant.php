<?php
  @ob_start();
  session_start();
  include 'inc/header.php';
  include 'inc/footer.php';
  include 'app/connect.php';
  include 'inc/models/RestaurantObj.php';
  include 'inc/models/ReviewObj.php';

  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    if(isset($_GET['restid'])) {
      // Populate RestaurantObj
      $restid = $_GET['restid'];
      $rest = new Restaurant($restid, $conn);

      // Get Review
      $reviews_sql = "SELECT * FROM Reviews WHERE restaurant_id='$restid'";
      $reviews_result = mysqli_query($conn, $reviews_sql);
      $reviews = [];
      if(mysqli_num_rows($reviews_result) > 0){
        while($row = mysqli_fetch_assoc($reviews_result)){
          $posted_by = $row['user_id'];
          $user_sql = "SELECT user_name FROM User WHERE user_id='$posted_by'";
          $user_result = mysqli_query($conn, $user_sql);
          $user_name = mysqli_fetch_assoc($user_result)["user_name"];
          $review = new Review($row['review_id'], $row['user_id'], $row['restaurant_id'], $row['review_text'], $row['rating'], $rest->restaurant_name, $user_name);
          array_push($reviews, $review);
        }
      }
    } else {
      header("location:javascript://history.go(-1)");
    }
  } else {
      header('Location: login.php');
  }
?>
  <div class="container">
    <div class="starter-template">
      <h1><?php echo $rest->restaurant_name; ?></h1>
      <div class="text-center">
          <div class="row"><?php if(isset($rest->address)){ echo $rest->address; }?></div>
          <div class="row"><?php if(isset($rest->pricerange)){ for($x = 0; $x < $rest->pricerange; $x++){ echo "$"; } }?></div>
          <div class="row"><?php if(isset($rest->rating)){ echo "Rating: " . $rest->rating; } ?></div>
          <div class="row"><?php if(isset($rest->votes)){ echo "Votes: " . $rest->votes; } ?></div>
          <div class="row"><?php if(isset($rest->delivers)){ if($rest->delivers == 0){echo "Delivers"; } else {echo "No Delivery"; } }?></div>
          <div class="row">
            <?php
              echo "Cuisines: ";
              foreach($rest->cuisine_names as &$cuisine){
                echo $cuisine . " ";
              }
            ?>
          </div>
          <div class="top-buffer"></div>
      </div>
      <div>
        <div class="row">
          <h2> Reviews </h2>
        </div>
        <?php if(count($reviews) == 0){ ?>
                  <h4> No Reviews Yet!<h4>
        <?php } else {
                  foreach($reviews as &$rev){?>
                    <div class="row">
                        <div class="col-6 col-md-8"><?php if(isset($rev->user_name)){ echo $rev->user_name; }?></div>
                        <div class="col-6 col-md-4"><?php if(isset($rev->rating)){ echo $rev->rating; }?></div>
                    </div>
                    <div class="row">
                      <div class="col-...">
                          <?php if(isset($rev->rating)){ echo $rev->review_text; }?>
                      </div>
                    </div>
                    <div class="row"><hr></div>
        <?php     }
              } ?>
      </div>
    </div>
  </div>
