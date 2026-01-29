<?php
require_once 'includes/db.php';
require_once 'classes/Categorie.php';

$foutmeldingen = [];
$succesbericht = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categorie_naam = trim($_POST['categorie']);
    $subcategorie_naam = trim($_POST['subcategorie']);
    $code = trim($_POST['code']);

    if(empty($categorie_naam)) {
        $foutmeldingen[] = 'Categorie naam is verplicht';
    }
    if(empty($code)) {
        $foutmeldingen[] = 'Code is verplicht';
    }

    if(count($foutmeldingen) == 0) {
        $categorieObj = new Categorie($pdo);
        $categorieObj->categorie = $categorie_naam;
        $categorieObj->subcategorie = !empty($subcategorie_naam) ? $subcategorie_naam : null;
        $categorieObj->code = $code;
        
        if($categorieObj->voegCategorieToe()) {
            if(isset($_POST['maak_aan_en_andere'])) {
                $succesbericht = 'Categorie succesvol toegevoegd! Je kan er nog een toevoegen.';
            } else {
                header('Location: artikelen.php');
                exit();
            }
        } else {
            $foutmeldingen[] = 'Er is iets misgegaan bij het toevoegen. Mogelijk bestaat deze code al.';
        }
    }
}

// Set pagina titel
$pageTitle = 'Nieuwe Categorie Toevoegen';
include 'includes/header.php';
?>

<!-- Categorie toevoegen formulier -->
<div class="container my-5">
    <h2 class="mb-4">Nieuwe Categorie Toevoegen</h2>

    <!-- Success/Fout berichten -->
    <?php if(!empty($succesbericht)): ?>
        <div class="alert alert-success"><?php echo $succesbericht; ?></div>
    <?php endif; ?>
    
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
                        <label for="categorie" class="form-label">Categorie Naam: <span class="text-danger">*</span></label>
                        <input type="text" id="categorie" name="categorie" class="form-control"
                               value="<?php echo isset($_POST['categorie']) ? $_POST['categorie'] : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="subcategorie" class="form-label">Subcategorie:</label>
                        <input type="text" id="subcategorie" name="subcategorie" class="form-control"
                               value="<?php echo isset($_POST['subcategorie']) ? $_POST['subcategorie'] : ''; ?>"
                               placeholder="Optioneel">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">Code: <span class="text-danger">*</span></label>
                        <input type="text" id="code" name="code" class="form-control"
                               value="<?php echo isset($_POST['code']) ? $_POST['code'] : ''; ?>" required
                               placeholder="Bijv. MEUB, KLEDING">
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" name="maak_aan" class="btn btn-primary">Categorie Aanmaken</button>
                    <button type="submit" name="maak_aan_en_andere" class="btn btn-success">Aanmaken en Nog een</button>
                    <a href="artikelen.php" class="btn btn-secondary">Terug naar Overzicht</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
