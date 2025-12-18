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

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <?php if(isset($_SESSION['id'])): ?>
    <a class="navbar-brand fw-bold" href="dashboard.php">MyFitTracker</a>
     <?php else: ?>
        <a class="navbar-brand fw-bold" href="index.php">MyFitTracker</a>
     <?php endif; ?>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
 <?php if(isset($_SESSION['id'])): ?>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    
        <li class="nav-item">
    <a class="nav-link <?= ($section == 'home') ? 'active' : '' ?>" href="dashboard.php?section=home">Početna</a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($section == 'foodAdd') ? 'active' : '' ?>" href="dashboard.php?section=foodAdd">Hrana</a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($section == 'weight') ? 'active' : '' ?>" href="dashboard.php?section=weight">Težina</a>
</li>

      </ul>
 <?php endif; ?>
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['id'])): ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Log out</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="signup.php">Sign up</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Log in</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
