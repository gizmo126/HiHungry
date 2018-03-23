<?php
@ob_start();
session_start();
    include 'inc/header.php';
    include 'inc/footer.php';
    include 'app/connect.php';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  if(isset($_POST['search'])){
    $loc = mysqli_real_escape_string($conn, $_POST['city']);
    $sql = "SELECT restaurant_name FROM Restaurant WHERE city ='$loc'";//create query
    $result = mysqli_query($conn, $sql);
    $restaurants = "";
    if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_assoc($result)){
        $restaurants .= $row["restaurant_name"] . "<br>";
      }
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
                <input type="text" class="form-login-txtbox" placeholder=" Enter City" name="city" required>
            </div>
            <button type="search" name="search" class="btn btn-primary">Search</button>
        </form>
      </div>
      <div><?php if(isset($restaurants)){ echo "<br>".$restaurants; } ?></div>
    </div>
  </div>
