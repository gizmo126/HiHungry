<?php
  @ob_start();
  session_start();
  include 'inc/header.php';
  include 'inc/footer.php';
  include 'app/connect.php';
  include 'inc/models/ReviewObj.php';
  include 'inc/deleteReviews.php';

  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    if(isset($_GET['userid'])) {

      $userid = $_GET['userid'];

      // Get Review
      $user_sql = "SELECT * FROM User WHERE user_id='$userid'";
      $user_result = mysqli_query($conn, $user_sql);
      $row = mysqli_fetch_assoc($user_result);
      $user_name = $row['user_name'];
      $Fname = $row['Fname'];
      $Lname = $row['Lname'];
      $url = $row['profile_url'];

      // get reviews for that user
      $reviews_sql = "SELECT * FROM Reviews WHERE user_id='$userid'";
      $reviews_result = mysqli_query($conn, $reviews_sql);
      $reviews = [];
      if(mysqli_num_rows($reviews_result) > 0){
        while($row = mysqli_fetch_assoc($reviews_result)){
          $review = new Review($row['review_id'], $conn, $user);
          array_push($reviews, $review);
        }
      }

      $num_reviews_sql = "SELECT user_id, COUNT(*) FROM `Reviews` WHERE user_id = $userid GROUP BY `user_id`";
      $num_result = mysqli_query($conn, $num_reviews_sql);
      $num = 0;
      if(mysqli_num_rows($num_result) > 0){
        $row1 = mysqli_fetch_assoc($num_result);
        $num = $row1['COUNT(*)'];
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
      <h1><?php echo $user_name; ?></h1>
      <p class="lead"><?php echo $Fname.' '.$Lname; ?></p>
      <?php
        if(!empty($url)){
          $imageData = base64_encode(file_get_contents($url));
          echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="img-thumbnail" style="width:25%">';
        } else {
          echo '<img src="http://www.personalbrandingblog.com/wp-content/uploads/2017/08/blank-profile-picture-973460_640-300x300.png" class="img-thumbnail" style="width:25%">';
        }
      ?>
      <div class="row">
        <h2> Reviews: <?php echo $num; ?> </h2>
      </div>
      <?php if($num == 0){ ?>
                <h4> No Reviews Yet!<h4>
      <?php } else {
                foreach($reviews as &$rev){?>
                  <div class="row"><hr></div>
                  <div class="row">
                      <?php echo
                            '<a href="restaurant.php?restid=' . $rev->restaurant_id . '">' .
                                '<div class="col-6 col-md-8">' . $rev->restaurant_name . '</div>' .
                            '</a>';
                      ?>
                      <div class="col-6 col-md-3"><?php if(isset($rev->rating)){ echo "Rating: " . $rev->rating; }?></div>
                      <div class="col-6 col-md-1">
                        <button data-toggle="modal" data-target="#deleteReviewModal" data-id="<?php echo $rev->review_id; ?>" class="btn btn-default">x</button>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-...">
                        <?php if(isset($rev->rating)){ echo mb_convert_encoding($rev->review_text, "HTML-ENTITIES", "UTF-8"); }?>
                    </div>
                  </div>
      <?php     }
            } ?>
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
