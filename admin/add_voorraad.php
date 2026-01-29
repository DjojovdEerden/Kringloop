<?php
// Database connectie laden
require_once '../includes/db.php';

// Form data verwerken
if ($_POST) {
    try {
        // POST data ophalen
        $artikel_id = $_POST['artikel_id'];
        $locatie = trim($_POST['locatie']);
        $aantal = (int)$_POST['aantal'];
        $status_id = $_POST['status_id'];

        // Check of hetzelfde item al bestaat (zelfde artikel, locatie en status)
        $checkStmt = $pdo->prepare("SELECT id, aantal FROM voorraad WHERE artikel_id = ? AND locatie = ? AND status_id = ?");
        $checkStmt->execute([$artikel_id, $locatie, $status_id]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update bestaande voorraad - tel aantal bij elkaar op
            $nieuw_aantal = $existing['aantal'] + $aantal;
            $updateStmt = $pdo->prepare("UPDATE voorraad SET aantal = ?, ingeboekt_op = NOW() WHERE id = ?");
            $updateStmt->execute([$nieuw_aantal, $existing['id']]);
            $success_message = "Aantal bijgewerkt! Van " . $existing['aantal'] . " naar " . $nieuw_aantal . " stuks.";
        } else {
            // Nieuwe voorraad toevoegen aan database
            $stmt = $pdo->prepare("INSERT INTO voorraad (artikel_id, locatie, aantal, status_id, ingeboekt_op) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$artikel_id, $locatie, $aantal, $status_id]);
            $success_message = "Voorraad succesvol toegevoegd!";
        }
    } catch (PDOException $e) {
        // Error bericht
        $error_message = "Fout bij toevoegen: " . $e->getMessage();
    }
}

// Haal artikelen en statussen op voor dropdowns
$artikelen = $pdo->query("SELECT id, naam FROM artikel ORDER BY naam")->fetchAll(PDO::FETCH_ASSOC);
$statussen = $pdo->query("SELECT id, status FROM status ORDER BY status")->fetchAll(PDO::FETCH_ASSOC);

// Set pagina titel
$pageTitle = 'Nieuwe Voorraad';
include '../includes/header.php';
?>

<!-- Voorraad toevoegen formulier -->
<div class="container my-5">
    <h2 class="mb-4 text-center">Nieuwe Voorraad Toevoegen</h2>
    
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
                                <option value="<?php echo $artikel['id']; ?>"><?php echo $artikel['naam']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="locatie" class="form-label">Locatie:</label>
                        <input type="text" name="locatie" id="locatie" class="form-control" placeholder="Bijvoorbeeld: Magazijn A3" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <!-- Aantal en status -->
                    <div class="col-md-6">
                        <label for="aantal" class="form-label">Aantal:</label>
                        <input type="number" name="aantal" id="aantal" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-6">
                        <label for="status_id" class="form-label">Status:</label>
                        <select name="status_id" id="status_id" class="form-select" required>
                            <option value="">Selecteer status...</option>
                            <?php foreach ($statussen as $status): ?>
                                <!-- Elke status als optie -->
                                <option value="<?php echo $status['id']; ?>"><?php echo $status['status']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Submit button -->
                <button type="submit" class="btn btn-primary w-100">Voorraad toevoegen</button>
            </form>
        </div>
    </div>
</div>

<?php
// Footer laden
include '../includes/footer.php';
?>