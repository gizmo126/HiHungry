<?php
@ob_start();
session_start();
    include 'inc/header.php';
    include 'inc/footer.php';
    include 'app/connect.php';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  if(isset($_POST['search'])){
    $loc = mysqli_real_escape_string($conn, $_POST['city']);
    $sql = "SELECT restaurant_name FROM Restaurant WHERE city ='$loc'";//query for restaurants
    $csn = mysqli_real_escape_string($conn, $_POST['cuisine']);
    $csn_marker = 0;
    $rest_marker = 0;
    if(!empty($csn)){
      $sql2 = "SELECT * FROM Cuisine WHERE cuisine_name='$csn'";
      $result2 = mysqli_query($conn, $sql2);                    // result2 holds cuisine search
      if(mysqli_num_rows($result2) < 1){
          $error = "Try another cuisine. Displaying all restaurants instead.";
      } else{
        $sql = "SELECT restaurant_name FROM Restaurant, Cuisine, Restaurant_Type WHERE city ='$loc'
                  AND Restaurant.restaurant_id = Restaurant_Type.restaurant_id
                  AND Restaurant_Type.cuisine_id = Cuisine.cuisine_id
                  AND cuisine_name = '$csn'";
            //update query for cuisine specifications
            $csn_marker = 1;
      }
    }
    $result = mysqli_query($conn, $sql);
    $restaurants = "";
    if(mysqli_num_rows($result) > 0){
      $rest_marker = 1;
      while($row = mysqli_fetch_assoc($result)){
        $restaurants .= $row["restaurant_name"] . "<br>";
      }
    } else{
      $error = "No results found.";
      $csn_marker = 0;
    }
  }
} else {
    header('Location: login.php');
}
?>
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
      <div><?php if(isset($restaurants)){ echo "<br>".$restaurants; } ?></div>
    </div>
  </div>
