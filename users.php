<?php
@ob_start();
session_start();
    include 'inc/header.php';
    include 'inc/footer.php';
    include 'app/connect.php';
    include 'inc/models/UserObj.php';
    include 'inc/deleteFriends.php';
    include 'inc/addFriends.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $u = $_SESSION['user'];
    $user_sql = "SELECT user_id FROM User WHERE user_name='$u'";
    $userresult = mysqli_query($conn, $user_sql);
    $row = mysqli_fetch_assoc($userresult);
    $user1id = $row["user_id"];

  if(isset($_POST['search'])){
    $firstname = mysqli_real_escape_string($conn, $_POST['fnm']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lnm']);
    if(empty($firstname) && empty($lastname)){
      $error = "Please enter a name";
    }
    else{
    	$sql = "SELECT * FROM User WHERE Fname LIKE '%$firstname%' AND Lname LIKE '%$lastname%'";  //Queries on both
      if(!empty($firstname) && empty($lastname)){ //only last name
        $sql = "SELECT * FROM User WHERE Fname LIKE '%$firstname%'";
        }
      if(empty($firstname) && !empty($lastname)){ //only first name
      	$sql = "SELECT * FROM User WHERE Lname LIKE '%$lastname%'";
      }
      $users = [];
      $user_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($user_result) > 0){
          while($row = mysqli_fetch_assoc($user_result)){
              $username = $row['user_name'];
              $firstname = $row['Fname'];
              $lastname = $row['Lname'];
              $user_id = $row['user_id'];
              $user = new User($user_id, $username, $firstname, $lastname);
              array_push($users, $user); //user object prob needed
          }
      } else{
        $error = "No Users Found.";
      }
    }
  }
} else {
    header('Location: login.php');
}
?>
      <div class="container">
    <div class="starter-template">
      <h1>Users</h1>
      <div class="text-center">
          <h2>Who would you like to look up?</h2>
          <br/>
          <form class="signup-form" action ="" method="POST">
            <div class="form-group">
                <input type="text" class="form-input-txtbox" placeholder="First Name" name="fnm">
                <input type="text" class="form-input-txtbox" placeholder="Last Name" name="lnm">
                <button type="search" name="search" class="btn btn-primary">Search</button>
            </div>
        </form>
      </div>
      <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
      <div class="row"><hr></div>
      <?php
          if(count($users)>0){
            foreach($users as & $u){?>
        <div class="row">
          <div class="col-6 col-md-2"></div>
          <div class="col-6 col-md-4">
            <?php
              if(!empty($u)){
                $imageData = base64_encode(file_get_contents("img/" . $u->user_id . ".jpg"));
                echo '<img src="data:image/jpeg;base64,'. $imageData .'" class="img-thumbnail" style="width:25%">';
              } else {
                echo '<img src="http://s3.amazonaws.com/cdn.roosterteeth.com/default/tb/user_profile_female.jpg" class="img-thumbnail" style="width:25%">';
              }
            ?>
          </div>
          <div class="col-6 col-md-2">
            <?php
                  echo '<h4>' . $u->Fname.' '.$u->Lname. '</h4>';
                  echo '<a href="user.php?userid=' . $u->user_id . '">' .
                          '<div>' . $u->user_name . '</div>' .
                          '</a>';
            ?>
          </div>
          <div class="col-6 col-md-1">
              <?php
                  $checkfriendsql = "SELECT * FROM Friend WHERE user1_id=$user1id AND user2_id=$u->user_id";
                  $checkfriendresult = mysqli_query($conn, $checkfriendsql);
                  if(mysqli_num_rows($checkfriendresult) == 0){ ?>
                    <button data-toggle="modal" data-target="#addFriendModal" data-id="<?php echo $u->user_id; ?>" class="btn btn-default">+</button>
                 <?php
                  } 

                  else{
                    ?>
                      <button data-toggle="modal" data-target="#deleteFriendModal" data-id="<?php echo $u->user_id; ?>" class="btn btn-default">x</button>
                <?php
                  }
              ?>
                          
                            
          </div>
          <div class="col-6 col-md-2"></div>
        </div>
        <div class="row"><hr></div>
      <?php }} ?>
    </div>
  </div>
