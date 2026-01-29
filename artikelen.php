<?php
require_once 'includes/db.php';
require_once 'classes/Artikel.php';
require_once 'classes/Categorie.php';

$artikel = new Artikel($pdo);
$categorieObj = new Categorie($pdo);
$categorien = $categorieObj->getAlleCategorien();

$zoekterm = isset($_GET['zoekterm']) ? trim($_GET['zoekterm']) : '';
$categorie_filter = isset($_GET['categorie']) ? trim($_GET['categorie']) : '';

if(!empty($zoekterm) || !empty($categorie_filter)) {
    $artikelen = $artikel->zoekArtikelen($zoekterm, $categorie_filter);
} else {
    $artikelen = $artikel->getAlleArtikelen();
}

$bericht = '';
if(isset($_GET['verwijderd']) && $_GET['verwijderd'] == 'success') {
    $bericht = 'Artikel succesvol verwijderd!';
}
if(isset($_GET['toegevoegd']) && $_GET['toegevoegd'] == 'success') {
    $bericht = 'Artikel succesvol toegevoegd!';
}

// Set pagina titel
$pageTitle = 'Artikelen Beheer';
include 'includes/header.php';
?>

<!-- Artikelen beheer pagina -->
<div class="container my-5">
    <?php if(!empty($bericht)): ?>
        <div class="alert alert-success"><?php echo $bericht; ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Artikelen Beheer</h2>
        <div>
            <a href="artikel_toevoegen.php" class="btn btn-primary me-2">Nieuw Artikel</a>
            <a href="categorie_toevoegen.php" class="btn btn-secondary">Nieuwe Categorie</a>
        </div>
    </div>

    <!-- Zoek en filter sectie -->
    <div class="card">
        <h1>Artikelen Overzicht</h1>
        
        <?php if($bericht != ''): ?>
            <div class="alert alert-success"><?php echo $bericht; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
            <!-- Zoek formulier -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-5">
                    <label for="zoekterm" class="form-label">Zoek op naam:</label>
                    <input type="text" id="zoekterm" name="zoekterm" class="form-control"
                           placeholder="Bijv. tafel, stoel..." 
                           value="<?php echo $zoekterm; ?>">
                </div>
                
                <div class="col-md-5">
                    <label for="categorie" class="form-label">Filter op categorie:</label>
                    <select id="categorie" name="categorie" class="form-select">
                        <option value="">-- Alle categorieën --</option>
                        <?php foreach($categorien as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"
                                    <?php echo ($categorie_filter == $cat['id']) ? 'selected' : ''; ?>>
                                <?php 
                                    echo $cat['categorie'];
                                    if($cat['subcategorie']) {
                                        echo ' - ' . $cat['subcategorie'];
                                    }
                                    echo ' (' . $cat['code'] . ')';
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Zoeken</button>
                </div>
            </form>

            <!-- Artikelen tabel -->

        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 45%;">Naam</th>
                    <th style="width: 25%;">Categorie</th>
                    <th style="width: 12%;">Prijs (ex. BTW)</th>
                    <th style="width: 10%;">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($artikelen) > 0): ?>
                    <?php foreach($artikelen as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($item['naam']); ?></strong></td>
                            <td>
                                <span class="categorie-badge">
                                    <?php echo htmlspecialchars($item['categorie']); ?>
                                </span>
                                <?php if($item['subcategorie']): ?>
                                    <span class="subcategorie-text">
                                        → <?php echo htmlspecialchars($item['subcategorie']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>&euro; <?php echo number_format($item['prijs_ex_btw'], 2, ',', '.'); ?></td>
                            <td>
                                <a href="artikel_verwijderen.php?id=<?php echo $item['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Weet je zeker dat je dit artikel wilt verwijderen?');">
                                        Verwijderen
                                    </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Geen artikelen gevonden
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
