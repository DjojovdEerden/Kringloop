<?php
// Database connectie laden
require_once '../includes/db.php';

// Haal alle voorraad data op met artikel en status informatie
$stmt = $pdo->query('
    SELECT v.*, a.naam AS artikel_naam, s.status AS status_naam
    FROM voorraad v
    JOIN artikel a ON v.artikel_id = a.id
    JOIN status s ON v.status_id = s.id
    ORDER BY v.ingeboekt_op DESC
');
// Converteer naar array
$voorraad_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pagina configuratie instellen
$pageTitle = 'Voorraad Overzicht';
include '../includes/header.php';
?>

<!-- Voorraad overzicht tabel -->
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Voorraad Overzicht</h2>
        <a href="add_voorraad.php" class="btn btn-primary">Nieuwe Voorraad</a>
    </div>
    
    <!-- Success/Error berichten -->
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
        <div class="alert alert-success">Voorraad item succesvol verwijderd!</div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
        <div class="alert alert-danger">Fout bij het verwijderen van het voorraad item.</div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <!-- Zoek en filter opties -->
            <div class="d-flex justify-content-between mb-3">
                <input type="text" class="form-control w-25" placeholder="Zoek voorraad...">
                <button class="btn btn-secondary">Filters wissen</button>
            </div>
            
            <!-- Voorraad data tabel -->
            <table class="table table-hover">
                <!-- Tabel koppen definitie -->
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Artikel</th>
                        <th>Locatie</th>
                        <th>Aantal</th>
                        <th>Status</th>
                        <th>Ingeboekt op</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <!-- Voorraad records weergeven -->
                <tbody>
                    <?php if (empty($voorraad_items)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Geen voorraad gevonden</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($voorraad_items as $item): ?>
                            <tr>
                                <!-- Voorraad ID -->
                                <td><?php echo $item['id']; ?></td>
                                <!-- Artikel naam -->
                                <td><?php echo $item['artikel_naam']; ?></td>
                                <!-- Locatie in magazijn -->
                                <td><?php echo $item['locatie']; ?></td>
                                <!-- Aantal items -->
                                <td><?php echo $item['aantal']; ?></td>
                                <!-- Status -->
                                <td><?php echo $item['status_naam']; ?></td>
                                <!-- Datum ingeboekt -->
                                <td><?php echo $item['ingeboekt_op']; ?></td>
                                <!-- Actie knoppen -->
                                <td>
                                    <a href="edit_voorraad.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-warning me-1">Bewerken</a>
                                    <a href="delete_voorraad.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je dit item wilt verwijderen?')">Verwijder</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Totaal statistieken -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5><?php echo count($voorraad_items); ?></h5>
                            <small>Totaal voorraad items</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5><?php echo array_sum(array_column($voorraad_items, 'aantal')); ?></h5>
                            <small>Totaal aantal stuks</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Footer laden
include '../includes/footer.php';
?>