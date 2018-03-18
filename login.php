<?php 

session_start();
ob_start();
	include 'inc/header.php';
	include 'inc/footer.php';
	include 'app/connect.php';
if(isset($_POST['submit'])){

 include_once 'app/connect.php';

  $unme = mysqli_real_escape_string($conn, $_POST['uname']);
  $upwd = mysqli_real_escape_string($conn, $_POST['psw']);

  if(empty($unme) || empty($upwd)){
    //echo "Fields cannot be empty";
  } 
  else {
      $sql = "SELECT * FROM User WHERE user_name='$unme' AND password='$upwd'";//create query
      $result = mysqli_query($conn, $sql);
      //$resultCheck = mysqli_num_rows($result);
      if(mysqli_num_rows($result) < 1){
      		$_SESSION['loggedin'] = false;
          $error = "Your Username or Password is invalid";
      } 
      else{
      		$_SESSION['loggedin'] = true;
      		$_SESSION['user'] = $unme;
      		header('Location: index.php');
            exit();
      }
  } 
}
ob_end_flush();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
</head>
<body>
    <div class="container-fluid text-center">
        <div class="row content">
            <div class="col-sm-2 sidenav">
            </div>
            <div>
                <div class="col-sm-8 text-center">
                    <h1>Welcome</h1>
                    <br/>
                    <form class="signup-form" action ="" method="POST">
                    	<div class="form-group">
                        	<label class="form-login-label" for="uname"><b>Username</b></label>
                        	<input type="text" class="form-login-txtbox" placeholder="Enter Username" name="uname" required>
                    	</div>
                    	<div class="form-group">
                        	<label class="form-login-label" for="psw"><b>Password</b></label>
                        	<input type="password" class="form-login-txtbox" placeholder="Enter Password" name="psw" required>
                   	 	</div>
                    	<div class="container text-center">
                        	<button type="submit" name="submit" class="btn btn-info">Login</button>
                    	</div>
               		</form>
               		<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
                </div>
            </div>
    </div>
    </div>  
</body>
</html>

