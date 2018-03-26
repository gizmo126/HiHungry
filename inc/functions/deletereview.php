<?php
  include '../../app/connect.php';
?>
<?php
    if(isset($_POST['id'])){
      $delete_id = $_POST['id'];
      $delete_sql = "DELETE FROM Reviews WHERE review_id=$delete_id";
      $delete_result = mysqli_query($conn, $delete_sql);
    }
?>
