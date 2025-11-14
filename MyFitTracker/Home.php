

<?php
session_start();
include 'db.php';

// Handle login
if(isset($_POST['login'])){
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if($user && password_verify($password,$user['password'])){
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $login_error = "Wrong email or password";
    }
}

// Handle signup
if(isset($_POST['signup'])){
    $email = $_POST['signup_email'];
    $password = password_hash($_POST['signup_password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users(email,password) VALUES (?,?)");
    $stmt->bind_param("ss",$email,$password);
    if($stmt->execute()){
        $_SESSION['user_id'] = $stmt->insert_id;
        header("Location: dashboard.php");
        exit();
    } else {
        $signup_error = "Email already exists";
    }
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

<!-- Navbar -->
<nav class="navbar navbar-expand-sm bg-light navbar-light border-bottom shadow-sm">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <a class="navbar-brand fw-bold mx-auto d-sm-none" href="#">MyFitTracker</a> 
    <a class="nav-link active fw-bold d-none d-sm-block" href="#">MyFitTracker</a> 

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="premium.php">Premium</a></li>
      </ul>
    </div>

    <ul class="navbar-nav ms-auto">
      <?php if(isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#signupModal">Sign Up</a></li>
        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Log In</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<!-- Home Content -->
<div class="container text-center py-5">
  <div class="p-5 bg-white rounded shadow-sm">
    <h1 class="fw-bold mb-3" style="background: linear-gradient(90deg, #007bff, #00c851); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
      Welcome to MyFitTracker
    </h1>
    <p class="lead text-muted mb-4">
      Take control of your fitness journey — track your eating habits, monitor your progress, and achieve your goals with ease.
    </p>
  </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Log In</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if(isset($login_error)) echo "<p class='text-danger'>$login_error</p>"; ?>
        <form method="post">
          <input type="email" name="login_email" class="form-control mb-2" placeholder="Email" required>
          <input type="password" name="login_password" class="form-control mb-2" placeholder="Password" required>
          <button type="submit" name="login" class="btn btn-primary w-100">Log In</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Signup Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sign Up</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if(isset($signup_error)) echo "<p class='text-danger'>$signup_error</p>"; ?>
        <form method="post">
          <input type="email" name="signup_email" class="form-control mb-2" placeholder="Email" required>
          <input type="password" name="signup_password" class="form-control mb-2" placeholder="Password" required>
          <button type="submit" name="signup" class="btn btn-success w-100">Sign Up</button>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>
