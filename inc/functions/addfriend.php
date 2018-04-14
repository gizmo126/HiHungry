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
      $add_friend_id = $_POST['id'];
      $add_sql = "INSERT INTO Friend (user1_id,user2_id) VALUES ($user_id, $add_friend_id)";
      $add_result = mysqli_query($conn, $add_sql);
    }
?>
