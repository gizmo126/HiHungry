<?php
  include '../../app/connect.php';
?>


<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $user = $_SESSION['user'];
    $user_sql = "SELECT user_id FROM User WHERE user_name='$user'";
    $user_result = mysqli_query($conn, $user_sql);
    $row = mysqli_fetch_assoc($user_result);
    $user_id = $row["user_id"];
}

    if(isset($_POST['id'])){
      $delete_friend_id = $_POST['id'];
      $delete_friend_sql = "DELETE FROM Friend WHERE user2_id=$delete_friend_id AND user1_id=$user_id";
      $delete_friend_result = mysqli_query($conn, $delete_friend_sql);
    }
?>
