<?php
// Database connectie laden
require_once '../includes/db.php';

// Haal alle planning data op
$stmt = $pdo->query('
    SELECT p.*, pe.voornaam, pe.achternaam, pe.adres, pe.type, a.naam AS artikel_naam
    FROM planning p
    JOIN personen pe ON p.persoon_id = pe.id
    JOIN artikel a ON p.artikel_id = a.id
    ORDER BY p.afspraak_op ASC
');
// Converteer naar array
$planningen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// Pagina configuratie instellen
$pageTitle = 'Planning Overzicht';
include '../includes/header.php';
?>

<!-- Planning overzicht tabel -->

<div class="container my-5">
    <h2 class="mb-4 text-center">Planning Overzicht</h2>
    <div class="card">
        <div class="card-body">
            <!-- Zoek en filter opties -->
            <div class="d-flex justify-content-between mb-3">
                <input type="text" class="form-control w-25" placeholder="Zoek planning...">
                <button class="btn btn-secondary">Filters wissen</button>
            </div>
            <!-- Planning data tabel -->
            <table class="table table-hover">
                <!-- Tabel koppen definitie -->
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Artikel</th>
                        <th>Persoon</th>
                        <th>Type</th>
                        <th>Kenteken</th>
                        <th>Ophalen/Bezorgen</th>
                        <th>Afspraak op</th>
                        <th>Omschrijving</th>
                        <th>Adres</th>
                    </tr>
                </thead>
                <!-- Planning records weergeven -->
                <tbody>
                    <?php foreach ($planningen as $planning): ?>
                        <tr>
                            <!-- Planning ID -->
                            <td><?php echo $planning['id']; ?></td>
                            <!-- Artikel naam -->
                            <td><?php echo $planning['artikel_naam']; ?></td>
                            <!-- Persoon volledige naam -->
                            <td><?php echo $planning['voornaam'] . ' ' . $planning['achternaam']; ?></td>
                            <!-- Type persoon -->
                            <td><?php echo $planning['type']; ?></td>
                            <!-- Auto kenteken -->
                            <td><?php echo $planning['kenteken']; ?></td>
                            <!-- Ophalen of bezorgen -->
                            <td><?php echo $planning['ophalen_of_bezorgen']; ?></td>
                            <!-- Afspraak datum en tijd -->
                            <td><?php echo $planning['afspraak_op']; ?></td>
                            <!-- Beschrijving -->
                            <td><?php echo $planning['omschrijving']; ?></td>
                            <!-- Adres van persoon -->
                            <td><?php echo $planning['adres']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Footer laden
include '../includes/footer.php';
?>