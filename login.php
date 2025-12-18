<?php
session_start();
require "connect.php"; 
require "includes\headerLogged.php"; 

$login_error = "";

if(isset($_POST['submit'])) {
    $e_mail = $_POST['e_mail'];
    $lozinka = $_POST['lozinka'];

    $sql = $conn->query("SELECT * FROM korisnici WHERE e_mail='$e_mail'");
    if($sql && $sql->num_rows === 1){
        $row = $sql->fetch_assoc();
        if(password_verify($lozinka, $row['lozinka'])){
            $_SESSION['prijavljen'] = true;
            $_SESSION['username'] = $row['e_mail'];
            $_SESSION['ime'] = $row['ime'];
            $_SESSION['prezime'] = $row['prezime'];
            $_SESSION['id'] = $row['id'];

            header("Location: dashboard.php");
            exit();
        } else {
            $login_error = "Pogrešan e-mail ili lozinka!";
        }
    } else {
        $login_error = "Pogrešan e-mail ili lozinka!";
    }
}
?>

  <div class="container d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <div class="col-md-5 col-lg-4 bg-white p-4 rounded shadow-sm">
      <div class="text-center mb-4">
        <h2 class="mt-3 fw-bold">Log in to your account</h2>
      </div>

      <form method="POST" action="">
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="e_mail" placeholder="name@example.com" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="lozinka" placeholder="••••••••" required>
        </div>

        <div class="d-grid">
          <button type="submit" name="submit" class="btn btn-primary fw-semibold">Log in</button>
        </div>
      </form>

      <?php if($login_error): ?>
        <div class="alert alert-danger mt-3"><?= $login_error ?></div>
      <?php endif; ?>

      <p class="text-center text-muted mt-4 mb-0">
        Don’t have an account? <a href="signup.php" class="text-decoration-none">Sign up</a>
      </p>
    </div>
  </div>

</body>
</html>

<?php
require "includes\\footer.php";
?>
