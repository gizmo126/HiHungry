<?php
  include '../../app/connect.php';
?>


<?php
    if(isset($_POST['id'])){
      $delete_friend_id = explode(",", $_POST['id']); //first part is friend; second part is user

      $delete_friend_sql = "DELETE FROM Friend WHERE user2_id=$delete_friend_id[0] AND user1_id=$delete_friend_id[1]";
      $delete_friend_result = mysqli_query($conn, $delete_friend_sql);
    }
?>
