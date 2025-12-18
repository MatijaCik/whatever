<?php

require_once "connect.php";
require_once "includes/headerLogged.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['id'];
$msg = "";

if(isset($_POST['submit'])) {
    $tezina = floatval($_POST['tezina']);
    if($tezina > 0) {
        $stmt = $conn->prepare("INSERT INTO tezina (korisnik_id, tezina_kg) VALUES (?, ?)");
        $stmt->bind_param("id", $user_id, $tezina);
        if($stmt->execute()) {
            $msg = "Težina spremljena!";
        } else {
            $msg = "Greška prilikom spremanja težine.";
        }
        $stmt->close();
    } else {
        $msg = "Molimo unesite valjanu težinu.";
    }
}

// Povijest težina za chart
$weights = $conn->prepare("SELECT datum, tezina_kg FROM tezina WHERE korisnik_id = ? ORDER BY datum ASC");
$weights->bind_param("i", $user_id);
$weights->execute();
$weightsResult = $weights->get_result();

$weightData = [];
$dates = [];
$values = [];
while($row = $weightsResult->fetch_assoc()) {
    $weightData[] = $row;
    $dates[] = date('d.m.Y', strtotime($row['datum']));
    $values[] = $row['tezina_kg'];
}
?>

<div class="container mt-4">
    <h2 class="mb-4">Praćenje Težine</h2>

    <!-- Weight Entry Form -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Unos Težine</h5>
                </div>
                <div class="card-body">
                    <?php if($msg): ?>
                        <div class="alert alert-info"><?= $msg ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Težina (kg)</label>
                            <input type="number" step="0.1" name="tezina" class="form-control" required placeholder="npr. 75.5">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Spremi Težinu</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Current Stats -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Trenutni Status</h5>
                </div>
                <div class="card-body">
                    <?php if(count($weightData) > 0): ?>
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-primary"><?= end($values) ?> kg</h4>
                                <small class="text-muted">Trenutna težina</small>
                            </div>
                            <div class="col-6">
                                <?php
                                $change = count($values) > 1 ? end($values) - $values[0] : 0;
                                $changeClass = $change > 0 ? 'text-danger' : ($change < 0 ? 'text-success' : 'text-muted');
                                $changeIcon = $change > 0 ? '↑' : ($change < 0 ? '↓' : '→');
                                ?>
                                <h4 class="<?= $changeClass ?>"><?= $changeIcon ?> <?= abs($change) ?> kg</h4>
                                <small class="text-muted">Promjena</small>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">Unesite svoju prvu težinu da vidite statistiku.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Weight Progress Chart -->
    <?php if(count($weightData) > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Grafikon Progres Težine</h5>
                </div>
                <div class="card-body">
                    <canvas id="weightChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Weight History Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Povijest Težina</h5>
                </div>
                <div class="card-body">
                    <?php if(count($weightData) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Datum</th>
                                        <th>Težina (kg)</th>
                                        <th>Promjena</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $prevWeight = null;
                                    foreach($weightData as $weight):
                                        $change = $prevWeight !== null ? $weight['tezina_kg'] - $prevWeight : 0;
                                        $changeText = $prevWeight !== null ? ($change > 0 ? "+$change" : $change) : "-";
                                        $changeClass = $change > 0 ? 'text-danger' : ($change < 0 ? 'text-success' : 'text-muted');
                                    ?>
                                    <tr>
                                        <td><?= date('d.m.Y', strtotime($weight['datum'])) ?></td>
                                        <td><?= $weight['tezina_kg'] ?> kg</td>
                                        <td class="<?= $changeClass ?>"><?= $changeText ?> kg</td>
                                    </tr>
                                    <?php
                                    $prevWeight = $weight['tezina_kg'];
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">Još nema unosa težine.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<?php if(count($weightData) > 0): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('weightChart').getContext('2d');
const weightChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($dates) ?>,
        datasets: [{
            label: 'Težina (kg)',
            data: <?= json_encode($values) ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            fill: true,
            pointBackgroundColor: 'rgb(75, 192, 192)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Praćenje Težine kroz Vrijeme'
            },
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                title: {
                    display: true,
                    text: 'Težina (kg)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Datum'
                }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    }
});
</script>
<?php endif; ?>

<?php include "includes/footer.php"; ?>