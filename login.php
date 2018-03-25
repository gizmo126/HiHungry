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
		$unme = mysqli_real_escape_string($conn, $_POST['uname_signup']);
		$upwd = mysqli_real_escape_string($conn, $_POST['psw_signup']);
		if(empty($unme) || empty($upwd)){
		}
		else {
				$sql = "SELECT * FROM User WHERE user_name='$unme'"; //create query
				$result = mysqli_query($conn, $sql);
				if(mysqli_num_rows($result) != 0){
					$error1 = "Username has been taken.";
	      	$error2 = "Username has been taken.";
	      } else {
					$sql2 = "INSERT INTO User (user_name, password) VALUES ('$unme', '$upwd')";
					$result = mysqli_query($conn, $sql2);
          $success = "Account created, please sign in.";
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
				<div class="row top-buffer">
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
											<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
											<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php if(isset($error2)){ echo $error2; } ?></div>
                      <div style = "font-size:11px; color:#006600; margin-top:10px"><?php if(isset($success)){ echo $success; } ?></div>
											<button type="login" name="login" class="btn btn-primary">Login</button>
											<div class="row top-buffer">
											<div class="row content">
												<h3>Need an Account?</h3>
												<button type="button" data-toggle="modal" data-target="#signupModal" class="btn btn-primary">Sign Up</button>
											</div>
									</form>
                </div>
            </div>
    		</div>
    </div>

		<!-- Modal -->
		<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="signupModalLabel">Sign Up</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form class="signup-form" id="signup-modal-form" action="" method="POST">
							<div class="form-group">
									<label class="form-login-label" for="uname"><b>Username</b></label>
									<input type="text" class="form-login-txtbox" placeholder=" Enter Username" name="uname_signup" required>
							</div>
							<div class="form-group">
									<label class="form-login-label" for="psw"><b>Password</b></label>
									<input type="password" class="form-login-txtbox" placeholder=" Enter Password" name="psw_signup" required>
							</div>
							<div class="modal-footer">
								<button name="cancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								<button name="signup" type="signup" class="btn btn-primary">Sign Up</button>
							</div>
							<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php if(isset($error2)){ echo $error2; } ?></div>
              <div style = "font-size:11px; color:#006600; margin-top:10px"><?php if(isset($success)){ echo $success; } ?></div>
            </form>
					</div>
				</div>
			</div>
		</div>
		<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="js/login.js" type="text/javascript"></script>
</body>
</html>
