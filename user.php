<?php
  @ob_start();
  session_start();
  include 'inc/header.php';
  include 'inc/footer.php';
  include 'app/connect.php';
  include 'inc/models/ReviewObj.php';
  include 'inc/models/RestaurantObj.php';
  include 'inc/models/UserObj.php';
      include 'inc/deleteFriends.php';
    include 'inc/addFriends.php';

  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

    $u = $_SESSION['user'];
    $user_sql1 = "SELECT user_id FROM User WHERE user_name='$u'";
    $userresult = mysqli_query($conn, $user_sql1);
    $row = mysqli_fetch_assoc($userresult);
    $user1id = $row["user_id"];

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
      $curr_user = $_SESSION['user'];
      if($curr_user == $user_name){
        header('Location: profile.php');
      }

      $friends_sql = "SELECT * FROM Friend, User WHERE Friend.user1_id='$userid' AND User.user_id=Friend.user2_id";
      $friends_result = mysqli_query($conn, $friends_sql);
      $friends = [];
      if(mysqli_num_rows($friends_result) > 0){
        while($row = mysqli_fetch_assoc($friends_result)){
              $username = $row['user_name'];
              $firstname = $row['Fname'];
              $lastname = $row['Lname'];
              $user_id = $row['user_id'];
              $friend = new User($user_id, $username, $firstname, $lastname);
          array_push($friends, $friend);
        }
      }


      $num_friends_sql = "SELECT user1_id, COUNT(*) FROM `Friend` WHERE user1_id = $userid GROUP BY `user1_id`";
      $num_friends_result = mysqli_query($conn, $num_friends_sql);
      $num_friends = 0;
      if(mysqli_num_rows($num_friends_result) > 0){
        $row2 = mysqli_fetch_assoc($num_friends_result);
        $num_friends = $row2['COUNT(*)'];
      }



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
      <div class = "row">
          <div class="col-6 col-md-12">
              <?php
                  $checkfriendsql = "SELECT * FROM Friend WHERE user1_id=$user1id AND user2_id=$userid";
                  $checkfriendresult = mysqli_query($conn, $checkfriendsql);
                  if(mysqli_num_rows($checkfriendresult) == 0){ ?>
                    <button data-toggle="modal" data-target="#addFriendModal" data-id="<?php echo $userid . ',' . $user1id; ?>" class="btn btn-success">Add Friend +</button>
                 <?php
                  }

                  else{
                    ?>
                      <button data-toggle="modal" data-target="#deleteFriendModal" data-id="<?php echo $userid . ',' . $user1id; ?>" class="btn btn-danger">Unfriend -</button>
                <?php
                  }
              ?>
              </div>

          </div>


      <div class ="row">
        <h2> Friends: <?php echo $num_friends; ?> </h2>
      </div>
      <?php if($num_friends ==0) { ?>
                <h4> No Friends Added! <h4>
      <?php } else {
                foreach($friends as &$fd){ ?>
                  <div class="row"><hr></div>
                  <div class="row">
                      <div class="col-6 col-md-4">
                        <?php
                          if(!empty($fd)){
                          $imageData = base64_encode(file_get_contents("img/" . $fd->user_id . ".jpg"));
                          echo '<img src="data:image/jpeg;base64,'. $imageData .'" class="img-thumbnail" style="width:25%">';
                          } else {
                            echo '<img src="http://s3.amazonaws.com/cdn.roosterteeth.com/default/tb/user_profile_female.jpg" class="img-thumbnail" style="width:25%">';
                          }
                        ?>

            </div>
          <div class="col-6 col-md-4">
            <?php
                  echo '<h4>' . $fd->Fname.' '.$fd->Lname. '</h4>';
                  echo '<a href="user.php?userid=' . $fd->user_id . '">' .
                          '<div>' . $fd->user_name . '</div>' .
                          '</a>';
            ?>
            </div>
        </div>
          <?php }
        } ?>


      <div class="row">
        <h2> Reviews: <?php echo $num; ?> </h2>
      </div>
      <?php if($num == 0){ ?>
                <h4> No Reviews Yet!<h4>
      <?php } else {
                foreach($reviews as &$rev){?>
                  <div class="row"><hr></div>
                  <div class="row">
                    <div class="col-6 col-md-4">
                      <?php
                        if(!empty($rev)){
                          $imageData = base64_encode(file_get_contents("img/" . $rev->restaurant_id . ".jpg"));
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
