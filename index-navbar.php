<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Log into the social network</title>
  <!-- Latest compiled and minified CSS -->

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

  <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #4d88ff
">
  <?php
    if (isset($_SESSION['u_id'])) {
      echo "<a class='navbar-brand' href='frontpage.php' id='frontPageBtn'>A Real Social Network</a>";
    } else {
       echo "<a class='navbar-brand' href='#' id='frontPageBtn'>A Real Social Network</a>";
    }
  ?>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php
        $url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        if (strpos($url,'frontpage') !== false || strpos($url,'event') !== false || strpos($url,'search') !== false || strpos($url, 'user-profile') !== false || strpos($url, 'shared-request') !== false) {
          echo "<li class='nav-item d-xl-none d-lg-none'><a href='frontpage.php'>Frontpage</a></li>
                <li class='nav-item d-xl-none d-lg-none'><a href='myevents.php'>My events</a></li>
                <li class='nav-item d-xl-none d-lg-none'><a href='shared-requests.php'>Friends</a></li>
                <li id='nav-logout' class='nav-item'><a href='includes/logout-function.php'>Log out</a></li>";
        } else {
          echo "<li class='nav-item active'>
                  <a class='nav-link' href='#' id='btn'>Log in <span class='sr-only'>(current)</span></a>
                </li>";
          echo "<li class='nav-item active'>
                  <a class='nav-link' href='#' id='btn2'>Sign in</a>
                </li>";
        }
      ?>
    </ul>
  </div>
</nav>
</br>
