<?php
session_start();
require_once "connect.php";

if (!isset($_SESSION['prijavljen']) || !$_SESSION['prijavljen']) {
    header("Location: login.php");
    exit();
}

$section = $_GET['section'] ?? 'home';
?>

<?php include "headerLoged.php"; ?>

<div class="container my-5">
    <?php
    switch ($section) {
        case 'weight':
            include "unos_kilaze";
            break;
        case 'food':
            include "unos_kalorija";  // sigurno postoji ova datoteka
            break;
        case 'home':
        default:
            include "home.php";
            break;
    }
    ?>
</div>

<?php include "footer.php"; ?>