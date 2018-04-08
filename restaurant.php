<?php
  @ob_start();
  session_start();
  include 'inc/header.php';
  include 'inc/footer.php';
  include 'app/connect.php';
  include 'inc/models/RestaurantObj.php';
  include 'inc/models/ReviewObj.php';
  include 'inc/deleteReviews.php';

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
              $review = new Review($row['review_id'], $conn, NULL);
              array_push($reviews, $review);
          }
      }

      $num_reviews_sql = "SELECT restaurant_id, COUNT(*) FROM `Reviews` WHERE restaurant_id = $restid GROUP BY `restaurant_id`";
      $num_result = mysqli_query($conn, $num_reviews_sql);
      $num = 0;
      if(mysqli_num_rows($num_result) > 0){
        $row1 = mysqli_fetch_assoc($num_result);
        $num = $row1['COUNT(*)'];
      }

      // Add New Review
      if(isset($_POST['review'])){
          // get user_id
          $user = $_SESSION['user'];
          $user_sql = "SELECT user_id FROM User WHERE user_name='$user'";
          $user_result = mysqli_query($conn, $user_sql);
          $user_id = mysqli_fetch_assoc($user_result)["user_id"];

          // set all the values to insert new review into Reviews
          $restaurant_id = $_GET['restid'];
          $review_rating = mysqli_real_escape_string($conn, $_POST['selected']);
          $review_text = mysqli_real_escape_string($conn, $_POST['text']);
          $insert_sql = "INSERT INTO Reviews (user_id, restaurant_id, review_text, rating)
                  VALUES ('$user_id', '$restaurant_id', '$review_text', '$review_rating')";
          $result = mysqli_query($conn, $insert_sql);

          // update the number of reviews (votes) that we have for this restaurant
          $rest->incrementVote($conn);

          // refresh page
          echo "<meta http-equiv='refresh' content='0'>";
      }

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
      <h1><?php echo $rest->restaurant_name; ?></h1>
      <?php
        if(!empty($rest->img_url)){
          $imageData = base64_encode(file_get_contents($rest->img_url));
          echo '<img src="data:image/jpeg;base64,'. $imageData .'" class="img-thumbnail" style="width:50%">';
        } else {
          echo '<img src="https://images.pexels.com/photos/262978/pexels-photo-262978.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" class="img-thumbnail" style="width:25%">';
        }
      ?>
      <div class="text-center">
          <div class="row"><?php if(isset($rest->address)){ echo $rest->address; }?></div>
          <div class="row"><?php if(isset($rest->pricerange)){ for($x = 0; $x < $rest->pricerange; $x++){ echo "$"; } } ?></div>
          <div class="row"></div>
          <div class="row"><?php if(isset($rest->delivers)){ if($rest->delivers == 0){echo "Delivers"; } else {echo "No Delivery"; } } ?></div>
          <div class="row">
            <?php
              echo "Cuisines: ";
              foreach($rest->cuisine_names as &$cuisine){
                echo $cuisine . " ";
              }
            ?>
          </div>
          <div class="top-buffer"></div>
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
      </div>
      <div>
        <div class="row">
          <h2> Reviews: <?php echo $num ?> </h2>
          <button type="button" data-toggle="modal" data-target="#addReviewModal" class="btn btn-primary">Add Review +</button>
        </div>
        <?php if(count($reviews) == 0){ ?>
                  <div class="row"><h4> No Reviews Yet!<h4></div>
        <?php } else {
                  foreach($reviews as &$rev){?>
                    <div class="row"><hr></div>
                    <div class="row">
                        <div class="col-6 col-md-4">
                        </div>
                        <div class="col-6 col-md-8">
                          <div class="row">
                            <?php echo
                                '<a href="user.php?userid=' . $rev->user_id . '">' .
                                    '<div class="col-6 col-md-6">' . $rev->user_name . '</div>' .
                                '</a>';
                            ?>
                            <div class="col-6 col-md-3"><?php if(isset($rev->rating)){ echo "Rating: " . $rev->rating; }?></div>
                              <?php if($rev->user_name == $_SESSION['user']){
                                      echo '<div class="col-6 col-md-1">
                                              <button data-toggle="modal" data-target="#deleteReviewModal" data-id="' . $rev->review_id . '" class="btn btn-default">x</button>
                                            </div>';
                                    }
                              ?>
                          </div>
                          <div class="row">
                            <div class="col-6 col-md-6">
                              <?php if(isset($rev->rating)){ echo mb_convert_encoding($rev->review_text, "HTML-ENTITIES", "UTF-8"); }?>
                            </div>
                          </div>
                        </div>
                    </div>
        <?php     }
              } ?>
      </div>
    </div>

    <!-- Add Review Modal -->
    <div class="modal fade" id="addReviewModal" tabindex="-1" role="dialog" aria-labelledby="addReviewLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            <h5 class="modal-title" id="addReviewLabel">Add Review</h5>
          </div>
          <div class="modal-body">
            <form class="signup-form" id="reviewForm" action="" method="POST">
              <div class="form-group">
                <label for="rating">Rating:</label>
                <select class="form-control" name="selected" required>
                  <option disabled selected value> -- Select a Rating -- </option>
                  <option>5</option>
                  <option>4</option>
                  <option>3</option>
                  <option>2</option>
                  <option>1</option>
                </select>
              </div>
              <div class="form-group">
                <label for="comment">Review:</label>
                <textarea class="form-control" rows="4" name="text" placeholder="Please tell us about your experience." required></textarea>
              </div>
              <div class="modal-footer">
                <button name="cancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button name="review" type="review" class="btn btn-primary">Add</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
