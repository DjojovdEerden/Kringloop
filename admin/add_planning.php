<?php
// Database connectie laden
require_once '../includes/db.php';

// Form data verwerken
if ($_POST) {
    try {
        // POST data ophalen
        $artikel_id = $_POST['artikel_id'];
        $klant_id = $_POST['klant_id'];
        $kenteken = $_POST['kenteken'];
        $ophalen_of_bezorgen = $_POST['ophalen_of_bezorgen'];
        $afspraak_op = $_POST['afspraak_op'];
        $omschrijving = $_POST['omschrijving'];

        // Planning toevoegen aan database
        $stmt = $pdo->prepare("INSERT INTO planning (artikel_id, klant_id, kenteken, ophalen_of_bezorgen, afspraak_op, omschrijving) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$artikel_id, $klant_id, $kenteken, $ophalen_of_bezorgen, $afspraak_op, $omschrijving]);
        
        // Success bericht
        $success_message = "Planning succesvol toegevoegd!";
    } catch (PDOException $e) {
        // Error bericht
        $error_message = "Fout bij toevoegen: " . $e->getMessage();
    }
}

// Set pagina titel
$pageTitle = 'Nieuwe Planning';
include '../includes/header.php';
?>

<!-- Planning toevoegen formulier -->
<div class="container my-5">
    <h2 class="mb-4 text-center">Nieuwe Planning Toevoegen</h2>
    
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
                    <div class="col-md-6">
                        <label for="artikel_id" class="form-label">Artikel ID:</label>
                        <input type="number" name="artikel_id" id="artikel_id" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="klant_id" class="form-label">Klant ID:</label>
                        <input type="number" name="klant_id" id="klant_id" class="form-control" required>
                    </div>
                </div>
                <!-- Transport en tijdstip velden -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kenteken" class="form-label">Kenteken:</label>
                        <input type="text" name="kenteken" id="kenteken" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <!-- Service type selectie -->
                        <label for="ophalen_of_bezorgen" class="form-label">Ophalen of Bezorgen:</label>
                        <select name="ophalen_of_bezorgen" id="ophalen_of_bezorgen" class="form-select">
                            <option value="ophalen">Ophalen</option>
                            <option value="bezorgen">Bezorgen</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <!-- Datum en beschrijving -->
                    <div class="col-md-6">
                        <label for="afspraak_op" class="form-label">Afspraak op (YYYY-MM-DD HH:MM:SS):</label>
                        <input type="text" name="afspraak_op" id="afspraak_op" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="omschrijving" class="form-label">Omschrijving:</label>
                        <input type="text" name="omschrijving" id="omschrijving" class="form-control" required>
                    </div>
                </div>
                <!-- Submit button -->
                <button type="submit" class="btn btn-primary w-100">Planning toevoegen</button>
            </form>
        </div>
    </div>
</div>

<?php
// Footer laden
include '../includes/footer.php';
?>