<?php
@ob_start();
session_start();
    include 'inc/header.php';
    include 'inc/footer.php';
    include 'app/connect.php';
    include 'inc/models/ReviewObj.php';
    include 'inc/models/RestaurantObj.php';
    include 'inc/deleteReviews.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    // get user_id
    $user = $_SESSION['user'];
    $user_sql = "SELECT user_id, profile_url FROM User WHERE user_name='$user'";
    $user_result = mysqli_query($conn, $user_sql);
    $row = mysqli_fetch_assoc($user_result);
    $user_id = $row["user_id"];
    $url = $row["profile_url"];

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

    $num_reviews_sql = "SELECT user_id, COUNT(*) FROM `Reviews` WHERE user_id = $user_id GROUP BY `user_id`";
    $num_result = mysqli_query($conn, $num_reviews_sql);
    $num = 0;
    if(mysqli_num_rows($num_result) > 0){
      $row1 = mysqli_fetch_assoc($num_result);
      $num = $row1['COUNT(*)'];
    }
      //print_r($num_result);
} else {
    header('Location: login.php');
}
?>
  <div class="container">
    <div class="starter-template">
      <h1>Profile</h1>
      <p class="lead">Hi <?php if(isset($user)){ echo $user; } ?></p>
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
      <?php if(count($reviews) == 0){ ?>
                <h4> No Reviews Yet!<h4>
      <?php } else {
                foreach($reviews as &$rev){?>
                  <div class="row"><hr></div>
                  <div class="row">
                      <div class="col-6 col-md-4">
                        <?php
                          $r = new Restaurant($rev->restaurant_id, $conn);
                          if(!empty($r->img_url)){
                            $imageData = base64_encode(file_get_contents($r->img_url));
                            echo '<img src="data:image/jpeg;base64,'. $imageData .'" class="img-thumbnail" style="width:25%">';
                          } else {
                            echo '<img src="https://images.pexels.com/photos/262978/pexels-photo-262978.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" class="img-thumbnail" style="width:25%">';
                          }
                        ?>
                      </div>
                      <div class="col-6 col-md-8">
                        <div class="row">
                          <?php echo
                            '<a href="restaurant.php?restid=' . $rev->restaurant_id . '">' .
                              '<div class="col-6 col-md-6">' . $rev->restaurant_name . '</div>' .
                            '</a>';
                          ?>
                          <div class="col-6 col-md-3"><?php if(isset($rev->rating)){ echo "Rating: " . $rev->rating; }?></div>
                          <div class="col-6 col-md-1">
                            <button data-toggle="modal" data-target="#deleteReviewModal" data-id="<?php echo $rev->review_id; ?>" class="btn btn-default">x</button>
                          </div>
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
  </div><!-- /.container -->
