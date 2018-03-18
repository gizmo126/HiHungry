<?php
@ob_start();
session_start();
    include 'inc/header.php';
    include 'inc/footer.php';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

} else {
    header('Location: login.php');
}
?>
    <div class="container">

      <div class="starter-template">
        <h1>HiHungry</h1>
        <p class="lead">Welcome to HiHungry</p>
      </div>

    </div><!-- /.container -->
