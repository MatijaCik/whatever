<?php
session_start();
require_once "connect.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$section = $_GET['section'] ?? 'home';
$today   = date('Y-m-d');

// === POVLAČENJE DNEVNOG CILJA IZ PROFILA ===
$stmtGoal = $conn->prepare("SELECT cilj_kcal FROM korisnici WHERE id = ?");
$stmtGoal->bind_param("i", $user_id);
$stmtGoal->execute();
$stmtGoal->bind_result($cilj_kcal);
$stmtGoal->fetch();
$stmtGoal->close();

// === RAČUNANJE UKUPNIH KALORIJA I MAKROA ===
$total_kcal = $total_proteini = $total_uh = $total_masti = 0;

$stmt = $conn->prepare("
    SELECT naziv, kalorije, proteini, ugljikohidrati, masti, kolicina, iz_baze
    FROM dnevni_unos
    WHERE korisnik_id = ? AND datum = ?
");
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$result = $stmt->get_result();

while ($r = $result->fetch_assoc()) {
    $faktor = $r['iz_baze'] ? ($r['kolicina'] / 100) : 1; // Ako je iz baze, množimo po 100g
    $total_kcal     += $r['kalorije'] * $faktor;
    $total_proteini += $r['proteini'] * $faktor;
    $total_uh       += $r['ugljikohidrati'] * $faktor;
    $total_masti    += $r['masti'] * $faktor;
}

// Zaokruživanje
$total_kcal     = round($total_kcal);
$total_proteini = round($total_proteini, 1);
$total_uh       = round($total_uh, 1);
$total_masti    = round($total_masti, 1);
$preostalo = $cilj_kcal - $total_kcal;

include "includes/headerLogged.php";
?>



<?php if ($section == 'home'): ?>

    
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Dobro došli natrag, <?= htmlspecialchars($_SESSION['ime']) ?>!</h2>
        <p class="lead text-muted"><?= date("l, d.m.Y") ?></p>
    </div>

    <!-- KALORIJE I MAKRO -->
    <div class="row g-4 mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm h-100 p-4 <?= $preostalo < 0 ? 'border-danger' : '' ?>">
                        <h3 class="fw-bold text-primary"><?= number_format($total_kcal) ?></h3>
                        <small class="text-muted">Potrošeno kcal</small>
                    </div>
                </div>

                <div class="col-md-3 col-6">
    <div class="card shadow-sm h-100 p-4">
        <h3 class="fw-bold text-success" id="ciljDisplay" style="cursor: pointer;"><?= $cilj_kcal ?></h3>
        <input type="number" id="ciljInput" value="<?= $cilj_kcal ?>" style="display:none; width: 100px;" min="0">
        <button id="ciljSave" class="btn btn-sm btn-success mt-2" style="display:none;">✔️</button>
        <small class="text-muted">Dnevni cilj</small>
    </div>
</div>

               <div class="col-md-3 col-6">
    <div class="card shadow-sm h-100 p-4 <?= $preostalo >= 0 ? 'border-success' : 'border-danger' ?>">
        <h3 class="fw-bold <?= $preostalo >= 0 ? 'text-success' : 'text-danger' ?>" id="preostaloDisplay">
            <?= $preostalo >= 0 ? $preostalo : 'Prekoračeno!' ?>
        </h3>
        <small class="text-muted">Preostalo</small>
    </div>
</div>

                <div class="col-md-3 col-6">
                    <div class="card shadow-sm h-100 p-4 bg-light">
                        <div class="row text-center">
                            <div class="col-4 border-end"><small>P</small><br><strong><?= $total_proteini ?>g</strong></div>
                            <div class="col-4 border-end"><small>UH</small><br><strong><?= $total_uh ?>g</strong></div>
                            <div class="col-4"><small>M</small><br><strong><?= $total_masti ?>g</strong></div>
                        </div>
                        <small class="text-muted d-block mt-2">Makroi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NAVIGACIJA -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <a href="?section=foodAdd" class="text-decoration-none <?= $section == 'foodAdd' ? 'active' : '' ?>">
                <div class="card h-100 text-center p-5 shadow-sm border-0">
                    <i class="bi bi-egg-fried" style="font-size: 3rem; color: #0d6efd;"></i>
                    <h5 class="mt-3">Hrana</h5>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="?section=weight" class="text-decoration-none <?= $section == 'weight' ? 'active' : '' ?>">
                <div class="card h-100 text-center p-5 shadow-sm border-0">
                    <i class="bi bi-graph-up" style="font-size: 3rem; color: #198754;"></i>
                    <h5 class="mt-3">Težina</h5>
                </div>
            </a>
        </div>
    </div>

    <?php endif; ?>
    <div class="mt-5">
        <?php
        switch ($section) {
            case 'weight':
                include "sections/weight.php";
                break;
            case 'foodAdd':
                include "sections/foodAdd.php";
                break;
            default:
                break;
        }
        ?>
    </div>
</div>


<script>
const ciljDisplay = document.getElementById('ciljDisplay');
const ciljInput = document.getElementById('ciljInput');
const ciljSave = document.getElementById('ciljSave');
const preostaloDisplay = document.getElementById('preostaloDisplay');

// ukupne kalorije - ovo je iz PHP-a
const totalKcal = <?= $total_kcal ?>;

ciljDisplay.addEventListener('click', () => {
    ciljDisplay.style.display = 'none';
    ciljInput.style.display = 'inline-block';
    ciljSave.style.display = 'inline-block';
    ciljInput.focus();
});

ciljSave.addEventListener('click', () => {
    const noviCilj = parseInt(ciljInput.value);

    fetch('update_cilj.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'novi_cilj=' + encodeURIComponent(noviCilj)
    })
    .then(response => response.text())
    .then(data => {
        if(data === 'OK'){
            // Ažuriranje cilja na stranici
            ciljDisplay.textContent = noviCilj;
            ciljDisplay.style.display = 'inline';
            ciljInput.style.display = 'none';
            ciljSave.style.display = 'none';

            // Preračun preostalih kalorija
            const preostalo = noviCilj - totalKcal;
            if(preostalo >= 0){
                preostaloDisplay.textContent = preostalo;
                preostaloDisplay.classList.remove('text-danger');
                preostaloDisplay.classList.add('text-success');
                preostaloDisplay.parentElement.classList.remove('border-danger');
                preostaloDisplay.parentElement.classList.add('border-success');
            } else {
                preostaloDisplay.textContent = 'Prekoračeno!';
                preostaloDisplay.classList.remove('text-success');
                preostaloDisplay.classList.add('text-danger');
                preostaloDisplay.parentElement.classList.remove('border-success');
                preostaloDisplay.parentElement.classList.add('border-danger');
            }

        } else {
            alert('Greška pri ažuriranju cilja!');
        }
    });
});

</script>

<?php include "includes/footer.php"; ?>


