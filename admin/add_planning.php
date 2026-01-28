<?php
$pageTitle = 'Nieuwe Planning';
include '../includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4 text-center">Nieuwe Planning Toevoegen</h2>
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
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kenteken" class="form-label">Kenteken:</label>
                        <input type="text" name="kenteken" id="kenteken" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="ophalen_of_bezorgen" class="form-label">Ophalen of Bezorgen:</label>
                        <select name="ophalen_of_bezorgen" id="ophalen_of_bezorgen" class="form-select">
                            <option value="ophalen">Ophalen</option>
                            <option value="bezorgen">Bezorgen</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="afspraak_op" class="form-label">Afspraak op (YYYY-MM-DD HH:MM:SS):</label>
                        <input type="text" name="afspraak_op" id="afspraak_op" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="omschrijving" class="form-label">Omschrijving:</label>
                        <input type="text" name="omschrijving" id="omschrijving" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Planning toevoegen</button>
            </form>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>