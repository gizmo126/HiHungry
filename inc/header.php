<?php
include_once "app/database.php";
header('Content-Type: text/html; charset=ISO-8859-1'); // cause special chars in restaurants are being a bitch
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/favicon.ico">

    <title>HiHungry</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet"/>
    <link href="css/standar-bootstrap.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>

    <!-- Fontawesome -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/index.php">HiHungry</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li <?php if($_SERVER['SCRIPT_NAME']=="/index.php") { ?> class="nav-item active" <?php } ?>>
              <a href="/index.php">Home</a>
            </li>
            <li <?php if($_SERVER['SCRIPT_NAME']=="/search.php") { ?> class="nav-item active" <?php } ?>>
              <a href="#search">Search</a></li>
            <li <?php if($_SERVER['SCRIPT_NAME']=="/restaurant.php") { ?> class="nav-item active" <?php } ?>>
              <a href="/restaurant.php">Restaurants</a>
            </li>
            <li <?php if($_SERVER['SCRIPT_NAME']=="/users.php") { ?> class="nav-item active" <?php } ?>>
              <a href="#users">Users</a>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li <?php if($_SERVER['SCRIPT_NAME']=="/profile.php") { ?> class="nav-item active" <?php } ?>>
                <a class="nav-link" href="profile.php"><i class="fa fa-user"></i> Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/inc/logout.php"><i class="fa fa-sign-out"></i> Logout</a>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
</html>
