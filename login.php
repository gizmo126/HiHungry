<?php

session_start();
ob_start();
	include 'app/connect.php';
if(isset($_POST['login'])){

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
elseif(isset($_POST['signup'])){
		include_once 'app/connect.php';
		$unme = mysqli_real_escape_string($conn, $_POST['uname']);
		$upwd = mysqli_real_escape_string($conn, $_POST['psw']);
		if(empty($unme) || empty($upwd)){
		}
		else{
				$sql = "SELECT * FROM User WHERE user_name='$unme'";//create query
				$result = mysqli_query($conn, $sql);
				if(mysqli_num_rows($result) != 0){
	      		$error = "Username has been taken.";
	      } else{
					$sql2 = "INSERT INTO User (user_name, password) VALUES ('$unme', '$upwd')";
					$result = mysqli_query($conn, $sql2);
				}
		}
}
ob_flush();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<!-- Custom styles for this template -->
		<link href="css/starter-template.css" rel="stylesheet"/>
		<link href="css/standar-bootstrap.css" rel="stylesheet"/>
		<link href="css/style.css" rel="stylesheet"/>
</head>
<body>
    <div class="container-fluid text-center">
        <div class="row content">
            <div class="col-sm-2 sidenav">
            </div>
            <div>
                <div class="col-sm-8 text-center">
                    <h1>Welcome to HiHungry</h1>
                    <br/>
                    <form class="signup-form" action ="" method="POST">
                    	<div class="form-group">
                        	<label class="form-login-label" for="uname"><b>Username</b></label>
                        	<input type="text" class="form-login-txtbox" placeholder=" Enter Username" name="uname" required>
                    	</div>
                    	<div class="form-group">
                        	<label class="form-login-label" for="psw"><b>Password</b></label>
                        	<input type="password" class="form-login-txtbox" placeholder=" Enter Password" name="psw" required>
                   	 	</div>
                      <button type="signup" name="signup" class="btn btn-info">Sign Up</button>
											<button type="login" name="login" class="btn btn-info">Login</button>
               		</form>
               		<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
                </div>
            </div>
    </div>
    </div>
		<script src="//code.jquery.com/jquery-1.10.1.min.js" type="text/javascript"></script>
		<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
