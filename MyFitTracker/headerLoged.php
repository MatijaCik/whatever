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
    <a class="navbar-brand fw-bold" href="loggedin.php">MyFitTracker</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="loggedin.php?section=home">My Home</a></li>
        <li class="nav-item"><a class="nav-link" href="loggedin.php?section=weight">Unos Kilograma</a></li>
        <li class="nav-item"><a class="nav-link" href="loggedin.php?section=food">Unos Hrane</a></li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['prijavljen']) && $_SESSION['prijavljen']): ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Log out</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="signup.php">Sign up</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Log in</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
