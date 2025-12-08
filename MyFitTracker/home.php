<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "connect.php";
require_once "headerLoged.php";

$user_id = $_SESSION['id'];
$today = date('Y-m-d');

$stmt = $conn->prepare("SELECT SUM(kalorije) as total FROM korisnicka_hrana WHERE korisnik_id = ? AND datum = ?");
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$totalCalories = $result['total'] ?? 0;
$stmt->close();

$dailyGoal = 2100;
$caloriesRemaining = $dailyGoal - $totalCalories;
?>

<h3>Dobrodošli, <?= htmlspecialchars($_SESSION['ime']); ?>!</h3>
<p>Preostale kalorije za danas: <strong><?= $caloriesRemaining ?></strong></p>