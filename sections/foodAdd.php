<?php

require_once "connect.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['id'];
$today = date('Y-m-d');
$selected_date = $_GET['date'] ?? $today; 



// =====================
// DODAVANJE HRANE (Ručni unos i AJAX iz baze)
// =====================


if (isset($_POST['add_food'])) {
    $naziv = $_POST['naziv'];
    $kalorije = floatval($_POST['kalorije']);
    $proteini = floatval($_POST['proteini']);
    $uh = floatval($_POST['ugljikohidrati']);
    $masti = floatval($_POST['masti']);
    $kolicina = floatval($_POST['kolicina']);
    $iz_baze = isset($_POST['iz_baze']) ? 1 : 0; 

    $stmt = $conn->prepare("
        INSERT INTO dnevni_unos (korisnik_id, naziv, kalorije, proteini, ugljikohidrati, masti, kolicina, datum, iz_baze)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isdddddsi", $user_id, $naziv, $kalorije, $proteini, $uh, $masti, $kolicina, $selected_date, $iz_baze);
    $stmt->execute();
    $stmt->close();

    if(isset($_POST['ajax'])) {
        echo json_encode(['success' => true]);
        exit();
    } else {
        header("Location: ?section=foodAdd&date={$selected_date}");
        exit();
    }
}

// =====================
// BRISANJE HRANE
// =====================
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM dnevni_unos WHERE id = ? AND korisnik_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: ?section=foodAdd&date={$selected_date}");
    exit();
}

// =====================
// UREĐIVANJE HRANE
// =====================
if (isset($_POST['edit_food'])) {
    $id = intval($_POST['id']);
    $naziv = $_POST['naziv'];
    $kalorije = floatval($_POST['kalorije']);
    $proteini = floatval($_POST['proteini']);
    $uh = floatval($_POST['ugljikohidrati']);
    $masti = floatval($_POST['masti']);
    $kolicina = floatval($_POST['kolicina']);

    $stmt = $conn->prepare("
        UPDATE dnevni_unos
        SET naziv=?, kalorije=?, proteini=?, ugljikohidrati=?, masti=?, kolicina=?
        WHERE id=? AND korisnik_id=?
    ");
    $stmt->bind_param("sdddddii", $naziv, $kalorije, $proteini, $uh, $masti, $kolicina, $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: ?section=foodAdd&date={$selected_date}");
    exit();
}

// =====================
// PRIKAZ DNEVNOG UNOSA
// =====================
$stmt = $conn->prepare("SELECT * FROM dnevni_unos WHERE korisnik_id=? AND datum=?");
$stmt->bind_param("is", $user_id, $selected_date);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h3>Dnevni unos hrane - <?= $selected_date ?></h3>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="?section=foodAdd&date=<?= date('Y-m-d', strtotime($selected_date . ' -1 day')) ?>" class="btn btn-outline-primary">&laquo; Prethodni dan</a>
        <input type="date" id="select-date" value="<?= $selected_date ?>" class="form-control w-auto">
        <a href="?section=foodAdd&date=<?= date('Y-m-d', strtotime($selected_date . ' +1 day')) ?>" class="btn btn-outline-primary">Sljedeći dan &raquo;</a>
    </div>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#manual" type="button">Ručni unos</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#database" type="button">Hrana iz baze</button>
        </li>
    </ul>

    <script>
    document.getElementById('select-date').addEventListener('change', function() {
        const selectedDate = this.value;
        window.location.href = `?section=foodAdd&date=${selectedDate}`;
    });
    </script>

    <div class="tab-content">
        <!-- RUČNI UNOS TAB -->
        <div class="tab-pane fade show active" id="manual">
            <form method="POST" class="mb-4">
                <div class="row g-2">
                    <div class="col"><input type="text" name="naziv" placeholder="Naziv hrane" class="form-control" required></div>
                    <div class="col"><input type="number" step="0.1" name="kalorije" placeholder="Kcal/100g" class="form-control" required></div>
                    <div class="col"><input type="number" step="0.1" name="proteini" placeholder="Proteini" class="form-control" required></div>
                    <div class="col"><input type="number" step="0.1" name="ugljikohidrati" placeholder="UH" class="form-control" required></div>
                    <div class="col"><input type="number" step="0.1" name="masti" placeholder="Masti" class="form-control" required></div>
                    <div class="col"><input type="number" step="0.1" name="kolicina" placeholder="Količina (g)" class="form-control" required></div>
                    <div class="col"><input type="hidden" name="iz_baze" value="0"><button type="submit" name="add_food" class="btn btn-primary w-100">Dodaj</button></div>
                </div>
            </form>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Naziv</th>
                        <th>Kcal(na 100g)</th>
                        <th>P</th>
                        <th>UH</th>
                        <th>M</th>
                        <th>Količina (g)</th>
                        <th>Akcija</th>
                    </tr>
                </thead>
                <tbody id="food-table-body">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr
                        data-id="<?= $row['id'] ?>"
                        data-naziv="<?= htmlspecialchars($row['naziv'], ENT_QUOTES) ?>"
                        data-kalorije="<?= $row['kalorije'] ?>"
                        data-proteini="<?= $row['proteini'] ?>"
                        data-ugljikohidrati="<?= $row['ugljikohidrati'] ?>"
                        data-masti="<?= $row['masti'] ?>"
                        data-kolicina="<?= $row['kolicina'] ?>"
                    >
                        <td><?= htmlspecialchars($row['naziv']) ?></td>
                        <td><?= $row['kalorije'] ?></td>
                        <td><?= $row['proteini'] ?></td>
                        <td><?= $row['ugljikohidrati'] ?></td>
                        <td><?= $row['masti'] ?></td>
                        <td><?= $row['kolicina'] ?></td>
                        <td>
                            <a href="?section=foodAdd&delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Obriši</a>
                            <a href="#" class="btn btn-primary btn-sm edit-link" data-bs-toggle="modal" data-bs-target="#editFoodModal">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- DATABASE TAB -->
        <div class="tab-pane fade" id="database">
            <div class="card">
                <div class="card-body">
                    <h5>Pretraga hrane iz baze</h5>
                    <input type="text" id="search-food" class="form-control mb-2" placeholder="Upiši naziv hrane">
                    <div id="search-results" class="list-group mb-2" style="max-height:200px; overflow-y:auto;"></div>

                    <div id="selected-food" style="display:none;">
                        <div class="row mb-2">
                            <div class="col"><input id="db_naziv" class="form-control" readonly></div>
                            <div class="col"><input id="db_kolicina" type="number" class="form-control" value="100" onchange="updateScaledValues()"></div>
                        </div>

                        <input type="hidden" id="db_kalorije_base">
                        <input type="hidden" id="db_proteini_base">
                        <input type="hidden" id="db_ugljikohidrati_base">
                        <input type="hidden" id="db_masti_base">

                        <div class="mb-2">
                            <input id="db_kalorije" class="form-control" readonly>
                            <input id="db_proteini" class="form-control" readonly>
                            <input id="db_ugljikohidrati" class="form-control" readonly>
                            <input id="db_masti" class="form-control" readonly>
                        </div>

                        <button id="add-db-food" class="btn btn-primary w-100 mt-2">Dodaj u dnevni unos</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editFoodModal" tabindex="-1" aria-labelledby="editFoodModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editFoodForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFoodModalLabel">Uredi hranu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
      </div>
      <div class="modal-body">
            <input type="hidden" name="id" id="edit-id">
            <div class="mb-3"><label for="edit-naziv" class="form-label">Naziv hrane</label><input type="text" name="naziv" id="edit-naziv" class="form-control" required></div>
            <div class="mb-3"><label for="edit-kalorije" class="form-label">Kcal/100g</label><input type="number" step="0.1" name="kalorije" id="edit-kalorije" class="form-control" required></div>
            <div class="mb-3"><label for="edit-proteini" class="form-label">Proteini</label><input type="number" step="0.1" name="proteini" id="edit-proteini" class="form-control" required></div>
            <div class="mb-3"><label for="edit-ugljikohidrati" class="form-label">UH</label><input type="number" step="0.1" name="ugljikohidrati" id="edit-ugljikohidrati" class="form-control" required></div>
            <div class="mb-3"><label for="edit-masti" class="form-label">Masti</label><input type="number" step="0.1" name="masti" id="edit-masti" class="form-control" required></div>
            <div class="mb-3"><label for="edit-kolicina" class="form-label">Količina (g)</label><input type="number" step="0.1" name="kolicina" id="edit-kolicina" class="form-control" required></div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit_food" class="btn btn-primary">Spremi promjene</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Odustani</button>
      </div>
    </form>
  </div>
</div>

<script>
document.querySelectorAll('.edit-link').forEach(link => {
    link.addEventListener('click', function() {
        const row = this.closest('tr');
        document.getElementById('edit-id').value = row.getAttribute('data-id');
        document.getElementById('edit-naziv').value = row.getAttribute('data-naziv');
        document.getElementById('edit-kalorije').value = row.getAttribute('data-kalorije');
        document.getElementById('edit-proteini').value = row.getAttribute('data-proteini');
        document.getElementById('edit-ugljikohidrati').value = row.getAttribute('data-ugljikohidrati');
        document.getElementById('edit-masti').value = row.getAttribute('data-masti');
        document.getElementById('edit-kolicina').value = row.getAttribute('data-kolicina');
    });
});
</script>



<!-- ////RAAAADI -->
<script>
let foodSearchInitialized = false;

document.addEventListener('shown.bs.tab', function (event) {
    if (event.target.getAttribute('data-bs-target') !== '#database') return;
    if (foodSearchInitialized) return;
    foodSearchInitialized = true;

    const searchInput = document.getElementById('search-food');
    const resultsDiv = document.getElementById('search-results');
    const selectedBox = document.getElementById('selected-food');
    let selectedFood = null;

    // 🔍 SEARCH
    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        resultsDiv.innerHTML = '';
        selectedBox.style.display = 'none';

        if (query.length < 1) return;

        fetch(`foodSearch.php?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    resultsDiv.innerHTML = '<div class="text-muted">Nema rezultata</div>';
                    return;
                }

                data.forEach(food => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `<strong>${food.naziv}</strong> — ${food.kalorije} kcal / 100g`;

                    item.onclick = () => selectFood(food);
                    resultsDiv.appendChild(item);
                });
            });
    });

    // ✅ ODABIR HRANE
    window.selectFood = function (food) {
        selectedFood = food;

        resultsDiv.innerHTML = ''; // ⛔ sakrij listu
        selectedBox.style.display = 'block';

        document.getElementById('db_naziv').value = food.naziv;
        document.getElementById('db_kalorije_base').value = food.kalorije;
        document.getElementById('db_proteini_base').value = food.proteini;
        document.getElementById('db_ugljikohidrati_base').value = food.ugljikohidrati;
        document.getElementById('db_masti_base').value = food.masti;

        document.getElementById('db_kolicina').value = 100;
        updateScaledValues();
    };

    // ⚖️ SKALIRANJE
    window.updateScaledValues = function () {
        if (!selectedFood) return;

        const grams = parseFloat(document.getElementById('db_kolicina').value) || 0;
        const factor = grams / 100;

        document.getElementById('db_kalorije').value = (selectedFood.kalorije * factor).toFixed(1);
        document.getElementById('db_proteini').value = (selectedFood.proteini * factor).toFixed(1);
        document.getElementById('db_ugljikohidrati').value = (selectedFood.ugljikohidrati * factor).toFixed(1);
        document.getElementById('db_masti').value = (selectedFood.masti * factor).toFixed(1);
    };

    // ➕ DODAVANJE HRANE IZ BAZE - BEZ AJAX-a (direktan submit)
    document.getElementById('add-db-food').addEventListener('click', function () {
        if (!selectedFood) return alert('Odaberite hranu');

        const grams = parseFloat(document.getElementById('db_kolicina').value) || 0;
        const factor = grams / 100;

        // Kreiraj skrivenu formu za slanje
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?section=foodAdd&date=<?= $selected_date ?>';
        
        // Dodaj sve potrebne inpute
        const addInput = (name, value) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        };

        addInput('naziv', selectedFood.naziv);
        addInput('kalorije', (selectedFood.kalorije * factor).toFixed(1));
        addInput('proteini', (selectedFood.proteini * factor).toFixed(1));
        addInput('ugljikohidrati', (selectedFood.ugljikohidrati * factor).toFixed(1));
        addInput('masti', (selectedFood.masti * factor).toFixed(1));
        addInput('kolicina', grams);
        addInput('iz_baze', '1');
        addInput('add_food', '1');

        // Dodaj formu u body i submitaj
        document.body.appendChild(form);
        form.submit();
    });
});

</script>



