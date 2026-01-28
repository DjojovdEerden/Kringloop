<?php
require_once '../includes/db.php';

$stmt = $pdo->query('
    SELECT p.*, k.naam AS klant_naam, k.adres AS klant_adres, a.naam AS artikel_naam
    FROM planning p
    JOIN klant k ON p.klant_id = k.id
    JOIN artikel a ON p.artikel_id = a.id
');
$planningen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$pageTitle = 'Planning Overzicht';
include '../includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4 text-center">Planning Overzicht</h2>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <input type="text" class="form-control w-25" placeholder="Zoek planning...">
                <button class="btn btn-secondary">Filters wissen</button>
            </div>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Artikel ID</th>
                        <th>Klant ID</th>
                        <th>Kenteken</th>
                        <th>Ophalen/Bezorgen</th>
                        <th>Afspraak op</th>
                        <th>Artikelnummer</th>
                        <th>Omschrijving</th>
                        <th>Klant Naam</th>
                        <th>Klant Adres</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($planningen as $planning): ?>
                        <tr>
                            <td><?php echo $planning['id']; ?></td>
                            <td><?php echo $planning['artikel_id']; ?></td>
                            <td><?php echo $planning['klant_id']; ?></td>
                            <td><?php echo $planning['kenteken']; ?></td>
                            <td><?php echo $planning['ophalen_of_bezorgen']; ?></td>
                            <td><?php echo $planning['afspraak_op']; ?></td>
                            <td><?php echo isset($planning['artikel_naam']) ? $planning['artikel_naam'] : 'N/A'; ?></td>
                            <td><?php echo $planning['omschrijving']; ?></td>
                            <td><?php echo $planning['klant_naam']; ?></td>
                            <td><?php echo $planning['klant_adres']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>