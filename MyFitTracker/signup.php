<?php
require "connect.php";
require "header.php";

$register_error = "";

if (isset($_POST['submit'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $e_mail = $_POST['e_mail'];
    $lozinka = password_hash($_POST['lozinka'], PASSWORD_DEFAULT);

    // provjera postoji li već
    $provjera = $conn->query("SELECT * FROM korisnici WHERE e_mail='$e_mail'");

    if ($provjera->num_rows > 0) {
        $register_error = "Korisnik s ovim e-mailom već postoji!";
    } else {
        $conn->query("INSERT INTO korisnici (ime, prezime, e_mail, lozinka, uloga)
                      VALUES ('$ime', '$prezime', '$e_mail', '$lozinka', 'user')");

        $_SESSION['prijavljen'] = true;
        $_SESSION['username'] = $e_mail;
        $_SESSION['ime'] = $ime;
        $_SESSION['prezime'] = $prezime;
        $_SESSION['administrator'] = false;
        $_SESSION['id'] = $conn->insert_id;

        header("Location: loggedin.php");
        exit();
    }
}
?>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <div class="col-md-5 col-lg-4 bg-white p-4 rounded shadow-sm">
      <div class="text-center mb-4">
        <h2 class="mt-3 fw-bold">Sign up for MyFitTracker</h2>
      </div>

      <?php if($register_error): ?>
        <div class="alert alert-danger"><?= $register_error ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label for="ime" class="form-label">Ime</label>
          <input type="text" class="form-control" id="ime" name="ime" required>
        </div>

        <div class="mb-3">
          <label for="prezime" class="form-label">Prezime</label>
          <input type="text" class="form-control" id="prezime" name="prezime" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" class="form-control" id="email" name="e_mail" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Lozinka</label>
          <input type="password" class="form-control" id="password" name="lozinka" required>
        </div>

        <div class="d-grid">
          <button type="submit" name="submit" class="btn btn-primary fw-semibold">Sign up</button>
        </div>
      </form>

      <p class="text-center text-muted mt-4 mb-0">
        Već imate račun? <a href="login.php" class="text-decoration-none">Log in</a>
      </p>
    </div>
</div>

<?php
require "footer.php";
?>
