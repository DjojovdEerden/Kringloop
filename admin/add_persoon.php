<?php
// Database connectie laden
require_once '../includes/db.php';

// Form data verwerken
if ($_POST) {
    try {
        // POST data ophalen
        $type = $_POST['type'];
        $voornaam = trim($_POST['voornaam']);
        $achternaam = trim($_POST['achternaam']);
        $adres = trim($_POST['adres']);
        $plaats = trim($_POST['plaats']);
        $postcode = trim($_POST['postcode']);
        $email = trim($_POST['email']);
        $telefoon = trim($_POST['telefoon']);
        $geboortedatum = $_POST['geboortedatum'] ? $_POST['geboortedatum'] : null;

        // Validatie - controleer of belangrijke velden zijn ingevuld
        if (empty($voornaam) || empty($achternaam) || empty($email)) {
            throw new Exception("Voornaam, achternaam en email zijn verplicht.");
        }

        // Controleer of email al bestaat
        $checkStmt = $pdo->prepare("SELECT 1 FROM personen WHERE email = ? AND actief = 1");
        $checkStmt->execute([$email]);
        if ($checkStmt->fetchColumn()) {
            throw new Exception("Email adres bestaat al.");
        }

        // Persoon toevoegen aan database
        $stmt = $pdo->prepare("INSERT INTO personen (type, voornaam, achternaam, adres, plaats, postcode, email, telefoon, geboortedatum) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$type, $voornaam, $achternaam, $adres, $plaats, $postcode, $email, $telefoon, $geboortedatum]);
        
        // Success bericht
        $success_message = "Persoon succesvol toegevoegd!";
    } catch (Exception $e) {
        // Error bericht
        $error_message = "Fout bij toevoegen: " . $e->getMessage();
    }
}

// Set pagina titel
$pageTitle = 'Nieuwe Persoon';
include '../includes/header.php';
?>

<!-- Persoon toevoegen formulier -->
<div class="container my-5">
    <h2 class="mb-4 text-center">Nieuwe Persoon Toevoegen</h2>
    
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
                    <!-- Type en naam velden -->
                    <div class="col-md-4">
                        <label for="type" class="form-label">Type:</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">Selecteer type...</option>
                            <option value="klant">Klant</option>
                            <option value="leverancier">Leverancier</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="voornaam" class="form-label">Voornaam:</label>
                        <input type="text" name="voornaam" id="voornaam" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="achternaam" class="form-label">Achternaam:</label>
                        <input type="text" name="achternaam" id="achternaam" class="form-control" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <!-- Adres informatie -->
                    <div class="col-md-6">
                        <label for="adres" class="form-label">Adres:</label>
                        <input type="text" name="adres" id="adres" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="postcode" class="form-label">Postcode:</label>
                        <input type="text" name="postcode" id="postcode" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="plaats" class="form-label">Plaats:</label>
                        <input type="text" name="plaats" id="plaats" class="form-control" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <!-- Contact informatie -->
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="telefoon" class="form-label">Telefoon:</label>
                        <input type="tel" name="telefoon" id="telefoon" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="geboortedatum" class="form-label">Geboortedatum (optioneel):</label>
                        <input type="date" name="geboortedatum" id="geboortedatum" class="form-control">
                    </div>
                </div>
                
                <!-- Submit button -->
                <button type="submit" class="btn btn-primary w-100">Persoon toevoegen</button>
            </form>
        </div>
    </div>
</div>

<?php
// Footer laden
include '../includes/footer.php';
?>