<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>MyFitTracker</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-sm bg-light navbar-light border-bottom shadow-sm">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <a class="navbar-brand fw-bold mx-auto d-sm-none" href="index.php">MyFitTracker</a> 
    <a class="nav-link active fw-bold d-none d-sm-block" href="index.php">MyFitTracker</a> 

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
       
      </ul>
    </div>

    <ul class="navbar-nav ms-auto">
      <?php if(!isset($_SESSION['prijavljen'])): ?>
        <li class="nav-item d-none d-sm-block">
          <a class="nav-link" href="signup.php">Sign up</a>
        </li>
        <li class="nav-item d-none d-sm-block">
          <a class="nav-link" href="login.php">Log in</a>
        </li>
      <?php else: ?>
        <li class="nav-item d-none d-sm-block">
          <a class="nav-link" href="logout.php">Log out</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
