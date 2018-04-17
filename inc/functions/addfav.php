<?php
  include '../../app/connect.php';
?>


<?php
    if(isset($_POST['id'])){
      $add_fav_id = explode(",", $_POST['id']);

      $add_sql = "INSERT INTO Favorite (user_id,restaurant_id) VALUES ($add_fav_id[0], $add_fav_id[1])";
      $add_result = mysqli_query($conn, $add_sql);
    }
?>
