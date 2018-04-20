<?php
@ob_start();
session_start();
    include 'inc/header.php';
    include 'inc/footer.php';
    include 'app/connect.php';
    include 'inc/models/UserObj.php';
    include 'inc/models/RestaurantObj.php';
    include 'inc/addFav.php';
    include 'inc/deleteFav.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  // get user_id
  $user = $_SESSION['user'];
  $user_sql = "SELECT user_id, profile_url FROM User WHERE user_name='$user'";
  $user_result = mysqli_query($conn, $user_sql);
  $row = mysqli_fetch_assoc($user_result);
  $user_id = $row["user_id"];

  $view_sql = "CREATE VIEW UserSearches AS SELECT * FROM Search WHERE user_id = $user_id";
  $view_result = mysqli_query($conn, $view_sql);
  $search_sql = "SELECT cuisine_id, COUNT(cuisine_id) FROM UserSearches GROUP BY cuisine_id ORDER BY COUNT(cuisine_id) DESC LIMIT 3";
  $search_result = mysqli_query($conn, $search_sql);
  $cuisines = [];
  if(mysqli_num_rows($search_result) > 0){
      while($row = mysqli_fetch_assoc($search_result)){
          $cuisine_id = $row['cuisine_id'];
          $cuisine_count = $row['COUNT(cuisine_id)'];
          $cuisine = new Cuisine($cuisine_id, $cuisine_count);
          array_push($cuisines, $cuisine);
      }
  }
  $count = 0;
  $recs = [];
  foreach ($cuisines as &$c){
      $recs_view_sql = "CREATE VIEW RestRecs AS SELECT Restaurant.restaurant_id, cuisine_id, rating FROM Restaurant, Restaurant_Type, Favorite
            WHERE user_id = $user_id AND Restaurant.restaurant_id = Restaurant_Type.restaurant_id AND Restaurant.restaurant_id = Favorite.restaurant_id";
      $recs_view_result = mysqli_query($conn, $recs_view_sql);
      $recommendation_sql = "SELECT * FROM RestRecs
            WHERE cuisine_id = $c->cuisine_id AND rating >= 4 LIMIT 5";
      $recommendation_result = mysqli_query($conn, $recommendation_sql);
      if(mysqli_num_rows($recommendation_result) > 0){
          while($row1 = mysqli_fetch_assoc($recommendation_result)){
              $rec = new Restaurant($row1['restaurant_id'], $conn);
              array_push($recs, $rec);
              $count++;
          }
      }
  }

  if(count($recs)<5){
    $recs = [];
    $count = 0;
    foreach ($cuisines as &$c){
        $rec_sql = "SELECT * FROM Restaurant, Restaurant_Type
              WHERE cuisine_id = $c->cuisine_id AND Restaurant.restaurant_id = Restaurant_Type.restaurant_id
              AND Restaurant.rating >= 4 LIMIT 3";
        $rec_result = mysqli_query($conn, $rec_sql);
        if(mysqli_num_rows($rec_result) > 0){
            while($row1 = mysqli_fetch_assoc($rec_result)){
                $rec = new Restaurant($row1['restaurant_id'], $conn);
                array_push($recs, $rec);
                if (++$count >= 5) break;
            }
        }
    }
  }

  if(count($recs)<5){
      $rec_sql = "SELECT restaurant_id FROM Restaurant WHERE rating = 5 LIMIT 5";
      $rec_result = mysqli_query($conn, $rec_sql);
      if(mysqli_num_rows($rec_result) > 0){
          while($row1 = mysqli_fetch_assoc($rec_result)){
              $rec = new Restaurant($row1['restaurant_id'], $conn);
              array_push($recs, $rec);
              if (++$count >= 5) break;
          }
      }
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
      <h1>HiHungry</h1>
      <p class="lead">Welcome to HiHungry</p>
      <div class="row"><hr></div>
      <p class="lead">Here are some recommendations! Or, click here to browse restaurants.
        <a href="restaurants.php" class="btn btn-link btn-lg" aria-pressed="true">Browse</a>
      </p>
    </div>
  <?php
    foreach($recs as &$r){?>
  <div class="row">
  <div class="col-8 col-md-4">
        <?php
          if(!empty($r)){
          $imageData = base64_encode(file_get_contents("img/" . $r->restaurant_id . ".jpg"));
          echo '<img src="data:image/jpeg;base64,'. $imageData .'" class="img-thumbnail" style="width:35%">';
          } else {
            echo '<img src="http://s3.amazonaws.com/cdn.roosterteeth.com/default/tb/user_profile_female.jpg" class="img-thumbnail" style="width:25%">';
          }
        ?>
  </div>
  <div class="col-8 col-md-8">
      <div class="col-8 col-md-6">
        <?php
            echo '<a href="restaurant.php?restid=' . $r->restaurant_id . '">' .
                    '<h4>' . $r->restaurant_name . '</h4>' .
                  '</a>';
        ?>
      </div>
      <div class="col-6 col-md-3"></div>
  </div>
  <div class="col-6 col-md-3">
      <div class="row" id="stars">
        <?php $counter = 0;
              while($counter < $r->rating){
                  echo '<span class="fa fa-star checked"></span>';
                  $counter++;
              }
              while($counter < 5){
                  echo '<span class="fa fa-star"></span>';
                  $counter++;
              }
        ?>
      </div>
  </div>
  <div class="col-6 col-md-1">
    <?php
        $checkfavsql = "SELECT * FROM Favorite WHERE user_id=$user_id AND restaurant_id=$r->restaurant_id";
        $checkfavresult = mysqli_query($conn, $checkfavsql);
        if(mysqli_num_rows($checkfavresult) == 0){ ?>
          <button data-toggle="modal" data-target="#addFavModal" data-id="<?php echo $user_id . ',' . $r->restaurant_id; ?>" class="btn btn-success">+</button>
       <?php
        }
        else{
          ?>
            <button data-toggle="modal" data-target="#deleteFavModal" data-id="<?php echo $user_id . ',' . $r->restaurant_id; ?>" class="btn btn-danger">x</button>
      <?php
        }
    ?>
  </div>
  </div>
  <div class="row"><hr></div>
  <?php   } ?>
  </div><!-- /.container -->
