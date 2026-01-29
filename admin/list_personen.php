<?php
// Database connectie laden
require_once '../includes/db.php';

// Filter opties
$type_filter = $_GET['type'] ?? '';
$search = $_GET['search'] ?? '';

// Haal alle personen data op met filters
$sql = "SELECT * FROM personen WHERE actief = 1";
$params = [];

if ($type_filter) {
    $sql .= " AND type = ?";
    $params[] = $type_filter;
}

if ($search) {
    $sql .= " AND (voornaam LIKE ? OR achternaam LIKE ? OR email LIKE ?)";
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$sql .= " ORDER BY achternaam, voornaam";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$personen = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pagina configuratie instellen
$pageTitle = 'Personen Overzicht';
include '../includes/header.php';
?>

<!-- Personen overzicht tabel -->
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Personen Overzicht</h2>
        <a href="add_persoon.php" class="btn btn-primary">Nieuwe Persoon</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <!-- Zoek en filter opties -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Zoek op naam of email...">
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">Alle types</option>
                        <option value="klant" <?php echo $type_filter === 'klant' ? 'selected' : ''; ?>>Klanten</option>
                        <option value="leverancier" <?php echo $type_filter === 'leverancier' ? 'selected' : ''; ?>>Leveranciers</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary">Zoeken</button>
                </div>
                <div class="col-md-3">
                    <a href="list_personen.php" class="btn btn-outline-secondary">Filters wissen</a>
                </div>
            </form>
            
            <!-- Personen data tabel -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <!-- Tabel koppen definitie -->
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Naam</th>
                            <th>Email</th>
                            <th>Telefoon</th>
                            <th>Plaats</th>
                            <th>Toegevoegd</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <!-- Personen records weergeven -->
                    <tbody>
                        <?php if (empty($personen)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">Geen personen gevonden</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($personen as $persoon): ?>
                                <tr>
                                    <td><?php echo $persoon['id']; ?></td>
                                    <td><?php echo $persoon['type']; ?></td>
                                    <td><?php echo $persoon['voornaam'] . ' ' . $persoon['achternaam']; ?></td>
                                    <td><?php echo $persoon['email']; ?></td>
                                    <td><?php echo $persoon['telefoon']; ?></td>
                                    <td><?php echo $persoon['plaats']; ?></td>
                                    <td><?php echo $persoon['datum_ingevoerd']; ?></td>
                                    <td>
                                        <a href="edit_persoon.php?id=<?php echo $persoon['id']; ?>" class="btn btn-sm btn-warning">Bewerken</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Statistieken -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h5><?php echo count(array_filter($personen, fn($p) => $p['type'] === 'klant')); ?></h5>
                            <small>Klanten</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5><?php echo count(array_filter($personen, fn($p) => $p['type'] === 'leverancier')); ?></h5>
                            <small>Leveranciers</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5><?php echo count($personen); ?></h5>
                            <small>Totaal</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Weet u zeker dat u deze persoon wilt verwijderen?')) {
        window.location.href = 'delete_persoon.php?id=' + id;
    }
}
</script>

<?php
// Footer laden
include '../includes/footer.php';
?>