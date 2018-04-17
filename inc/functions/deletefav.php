<?php
  include '../../app/connect.php';
?>


<?php
    if(isset($_POST['id'])){
      $delete_fav_id = explode(",", $_POST['id']); //first part is user; second part is restaurant

      $delete_fav_sql = "DELETE FROM Favorite WHERE user_id=$delete_fav_id[0] AND restaurant_id=$delete_fav_id[1]";
      $delete_fav_result = mysqli_query($conn, $delete_fav_sql);
    }
?>
