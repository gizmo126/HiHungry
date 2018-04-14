<?php
  include '../../app/connect.php';
?>


<?php
    if(isset($_POST['id'])){
      $add_friend_id = explode(",", $_POST['id']); //first part is friend; second part is user

      $add_sql = "INSERT INTO Friend (user1_id,user2_id) VALUES ($add_friend_id[1], $add_friend_id[0])";
      $add_result = mysqli_query($conn, $add_sql);
    }
?>
