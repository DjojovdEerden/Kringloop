<?php
// Database connectie laden
require_once '../includes/db.php';

// Check of ID is meegegeven
if (!isset($_GET['id'])) {
    header('Location: list_voorraad.php');
    exit;
}

$voorraad_id = (int)$_GET['id'];

// Form data verwerken
if ($_POST) {
    try {
        // POST data ophalen
        $artikel_id = $_POST['artikel_id'];
        $locatie = trim($_POST['locatie']);
        $aantal = (int)$_POST['aantal'];
        $status_id = $_POST['status_id'];

        // Voorraad bijwerken in database
        $stmt = $pdo->prepare("UPDATE voorraad SET artikel_id = ?, locatie = ?, aantal = ?, status_id = ? WHERE id = ?");
        $stmt->execute([$artikel_id, $locatie, $aantal, $status_id, $voorraad_id]);
        
        // Success bericht
        $success_message = "Voorraad succesvol bijgewerkt!";
    } catch (PDOException $e) {
        // Error bericht
        $error_message = "Fout bij bijwerken: " . $e->getMessage();
    }
}

// Haal huidige voorraad gegevens op
$stmt = $pdo->prepare("SELECT * FROM voorraad WHERE id = ?");
$stmt->execute([$voorraad_id]);
$voorraad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$voorraad) {
    header('Location: list_voorraad.php');
    exit;
}

// Haal artikelen en statussen op voor dropdowns
$artikelen = $pdo->query("SELECT id, naam FROM artikel ORDER BY naam")->fetchAll(PDO::FETCH_ASSOC);
$statussen = $pdo->query("SELECT id, status FROM status ORDER BY status")->fetchAll(PDO::FETCH_ASSOC);

// Set pagina titel
$pageTitle = 'Voorraad Bewerken';
include '../includes/header.php';
?>

<!-- Voorraad bewerken formulier -->
<div class="container my-5">
    <h2 class="mb-4 text-center">Voorraad Bewerken</h2>
    
    <!-- Success/Error berichten -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <!-- Artikel selectie -->
                    <div class="col-md-6">
                        <label for="artikel_id" class="form-label">Artikel:</label>
                        <select name="artikel_id" id="artikel_id" class="form-select" required>
                            <option value="">Selecteer artikel...</option>
                            <?php foreach ($artikelen as $artikel): ?>
                                <!-- Elk artikel als optie -->
                                <option value="<?php echo $artikel['id']; ?>" <?php echo $artikel['id'] == $voorraad['artikel_id'] ? 'selected' : ''; ?>>
                                    <?php echo $artikel['naam']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="locatie" class="form-label">Locatie:</label>
                        <input type="text" name="locatie" id="locatie" class="form-control" value="<?php echo $voorraad['locatie']; ?>" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <!-- Aantal en status -->
                    <div class="col-md-6">
                        <label for="aantal" class="form-label">Aantal:</label>
                        <input type="number" name="aantal" id="aantal" class="form-control" value="<?php echo $voorraad['aantal']; ?>" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="status_id" class="form-label">Status:</label>
                        <select name="status_id" id="status_id" class="form-select" required>
                            <option value="">Selecteer status...</option>
                            <?php foreach ($statussen as $status): ?>
                                <!-- Elke status als optie -->
                                <option value="<?php echo $status['id']; ?>" <?php echo $status['id'] == $voorraad['status_id'] ? 'selected' : ''; ?>>
                                    <?php echo $status['status']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary w-100">Voorraad bijwerken</button>
                    </div>
                    <div class="col-md-6">
                        <a href="list_voorraad.php" class="btn btn-secondary w-100">Terug naar overzicht</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Footer laden
include '../includes/footer.php';
?>