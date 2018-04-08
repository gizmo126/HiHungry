<?php
@ob_start();
session_start();
    include 'inc/header.php';
    include 'inc/footer.php';
    include 'app/connect.php';
    include 'inc/models/RestaurantObj.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  if(isset($_POST['search'])){
    $loc = mysqli_real_escape_string($conn, $_POST['city']);
    $sql = "SELECT restaurant_name, restaurant_id FROM Restaurant WHERE city ='$loc'";//query for restaurants
    $csn = mysqli_real_escape_string($conn, $_POST['cuisine']);
    $csn_marker = 0;
    $rest_marker = 0;
    if(empty($loc)){
      $error = "Please provide a location";
    }
    else{
      if(!empty($csn)){
        $sql2 = "SELECT * FROM Cuisine WHERE cuisine_name='$csn'";
        $result2 = mysqli_query($conn, $sql2);                    // result2 holds cuisine search
        if(mysqli_num_rows($result2) < 1){
            $error = "Try another cuisine. Displaying all restaurants instead.";
        } else{
          $sql = "SELECT restaurant_name, Restaurant.restaurant_id FROM Restaurant, Cuisine, Restaurant_Type WHERE city ='$loc'
                    AND Restaurant.restaurant_id = Restaurant_Type.restaurant_id
                    AND Restaurant_Type.cuisine_id = Cuisine.cuisine_id
                    AND cuisine_name = '$csn'";
              //update query for cuisine specifications
              $csn_marker = 1;
        }
      }
      $restaurants = [];
      $rest_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rest_result) > 0){
          $rest_marker = 1;
          while($row = mysqli_fetch_assoc($rest_result)){
              $rest_id = $row['restaurant_id'];
              $restaurant = new Restaurant($rest_id, $conn);
              array_push($restaurants, $restaurant);
          }
      } else{
        $error = "No results found.";
        $csn_marker = 0;
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
      <h1>Restaurants</h1>
      <div class="text-center">
          <h1>Where Are You?</h1>
          <br/>
          <form class="signup-form" action ="" method="POST">
            <div class="form-group">
                <input type="text" class="form-input-txtbox" placeholder=" Enter City" name="city">
                <input type="text" class="form-input-txtbox" placeholder=" Enter Cuisine" name="cuisine">
                <button type="search" name="search" class="btn btn-primary">Search</button>
            </div>
        </form>
      </div>
      <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
      <div style = "font-size:30px"><?php if($rest_marker && isset($loc)){ echo "<br>".$loc; } ?></div>
      <div style = "font-size:20px">
        <?php
          if(isset($csn) && $csn_marker == 0 && $rest_marker){ echo "All<br>"; }
          elseif($rest_marker && $csn_marker == 1){ echo $csn."<br>"; } ?>
      </div>
      <div class="row"><hr></div>
      <?php
          if(count($restaurants)>0){
            foreach($restaurants as &$r){?>
            <div class="row">
              <div class="col-6 col-md-4">
                <?php
                  if(!empty($r)){
                    $imageData = base64_encode(file_get_contents("img/" . $r->restaurant_id . ".jpg"));
                    echo '<img src="data:image/jpeg;base64,'. $imageData .'" class="img-thumbnail" style="width:25%">';
                  } else {
                    echo '<img src="https://images.pexels.com/photos/262978/pexels-photo-262978.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" class="img-thumbnail" style="width:25%">';
                  }
                ?>
              </div>
              <div class="col-6 col-md-4">
                <?php echo
                      '<a href="restaurant.php?restid=' . $r->restaurant_id . '">' .
                          '<div>' . $r->restaurant_name . '</div>' .
                      '</a>';
                ?>
              </div>
              <div class="col-6 col-md-4">
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
            </div>
        <div class="row"><hr></div>
      <?php }} ?>
    </div>
  </div>
