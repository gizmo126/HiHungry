<?php
@ob_start();
session_start();
    include 'inc/header.php';
    include 'inc/footer.php';
    include 'app/connect.php';
    include 'inc/models/ReviewObj.php';
    include 'inc/deleteReviews.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    // get user_id
    $user = $_SESSION['user'];
    $user_sql = "SELECT user_id FROM User WHERE user_name='$user'";
    $user_result = mysqli_query($conn, $user_sql);
    $user_id = mysqli_fetch_assoc($user_result)["user_id"];

    // get reviews for that user
    $reviews_sql = "SELECT * FROM Reviews WHERE user_id='$user_id'";
    $reviews_result = mysqli_query($conn, $reviews_sql);
    $reviews = [];
    if(mysqli_num_rows($reviews_result) > 0){
      while($row = mysqli_fetch_assoc($reviews_result)){
        $review = new Review($row['review_id'], $conn, $user);
        array_push($reviews, $review);
      }
    }
    // print_r($reviews);
} else {
    header('Location: login.php');
}
?>
  <div class="container">
    <div class="starter-template">
      <h1>Profile</h1>
      <p class="lead">Hi <?php if(isset($user)){ echo $user; } ?></p>
      <div class="row">
        <h2> Reviews </h2>
      </div>
      <?php if(count($reviews) == 0){ ?>
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
                        <?php if(isset($rev->rating)){ echo $rev->review_text; }?>
                    </div>
                  </div>
      <?php     }
            } ?>
    </div>
  </div><!-- /.container -->
