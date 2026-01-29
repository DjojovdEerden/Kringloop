<?php
require_once 'includes/db.php';
require_once 'classes/Artikel.php';
require_once 'classes/Categorie.php';

$foutmeldingen = [];
$categorieObj = new Categorie($pdo);
$categorien = $categorieObj->getAlleCategorien();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $naam = trim($_POST['naam']);
    $categorie_id = trim($_POST['categorie_id']);
    $prijs = trim($_POST['prijs']);

    if(empty($naam)) {
        $foutmeldingen[] = 'Naam is verplicht';
    }
    if(empty($categorie_id)) {
        $foutmeldingen[] = 'Selecteer een categorie';
    }
    if(empty($prijs)) {
        $foutmeldingen[] = 'Prijs is verplicht';
    }
    if(!is_numeric($prijs)) {
        $foutmeldingen[] = 'Prijs moet een getal zijn';
    }

    if(count($foutmeldingen) == 0) {
        $artikel = new Artikel($pdo);
        $artikel->naam = $naam;
        $artikel->categorie_id = $categorie_id;
        $artikel->prijs_ex_btw = $prijs;
        
        if($artikel->voegArtikelToe()) {
            header('Location: artikelen.php?toegevoegd=success');
            exit();
        } else {
            $foutmeldingen[] = 'Er is iets misgegaan bij het toevoegen';
        }
    }
}

// Set pagina titel
$pageTitle = 'Nieuw Artikel Toevoegen';
include 'includes/header.php';
?>

<!-- Artikel toevoegen formulier -->
<div class="container my-5">
    <h2 class="mb-4">Nieuw Artikel Toevoegen</h2>

    <!-- Foutmeldingen -->
    <?php if(count($foutmeldingen) > 0): ?>
        <div class="alert alert-danger">
            <?php foreach($foutmeldingen as $fout): ?>
                <p class="mb-1"><?php echo $fout; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="naam" class="form-label">Naam: <span class="text-danger">*</span></label>
                        <input type="text" id="naam" name="naam" class="form-control"
                               value="<?php echo isset($_POST['naam']) ? $_POST['naam'] : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="categorie_id" class="form-label">Categorie: <span class="text-danger">*</span></label>
                        <select id="categorie_id" name="categorie_id" class="form-select" required>
                            <option value="">-- Selecteer categorie --</option>
                            <?php foreach($categorien as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"
                                        <?php echo (isset($_POST['categorie_id']) && $_POST['categorie_id'] == $cat['id']) ? 'selected' : ''; ?>>
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
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="prijs" class="form-label">Prijs (ex. BTW): <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number" id="prijs" name="prijs" class="form-control" step="0.01"
                                   value="<?php echo isset($_POST['prijs']) ? $_POST['prijs'] : ''; ?>" required>
                        </div>
                        <small id="btw-info" class="form-text text-muted"></small>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Artikel Toevoegen</button>
                    <a href="artikelen.php" class="btn btn-secondary">Terug naar Overzicht</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function berekenBTW() {
            const exBTW = parseFloat(document.getElementById('prijs').value) || 0;
            const incBTW = exBTW * 1.21;
            document.getElementById('btw-info').textContent = 
                `Prijs inc. 21% BTW: € ${incBTW.toFixed(2).replace('.', ',')}`;
        }
        
        document.getElementById('prijs').addEventListener('input', berekenBTW);
    </script>
</div>

<?php include 'includes/footer.php'; ?>
