<?php
$pageTitle = 'Kringloop centrum - Home';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row g-4">
        <!-- Ritten Kaart -->
        <div class="col-md-6">
            <div class="card card-custom">
                <h3 class="mb-3">Ritten</h3>
                <p class="text-muted">beschrijving</p>
                <a href="ritten.php" class="btn btn-primary-custom mt-auto">Ga naar ritten</a>
            </div>
        </div>

        <!-- Voorraadbeheer Kaart -->
        <div class="col-md-6">
            <div class="card card-custom">
                <h3 class="mb-3">Voorraad beheer</h3>
                <p class="text-muted">Beschrijving</p>
                <a href="voorraad.php" class="btn btn-primary-custom mt-auto">Ga naar voorraad beheer</a>
            </div>
        </div>

        <!-- Kledingstukken Kaart -->
        <div class="col-md-6">
            <div class="card card-custom">
                <h3 class="mb-3">Kledingstukken</h3>
                <p class="text-muted">Beschrijving</p>
                <a href="kledingstukken.php" class="btn btn-primary-custom mt-auto">Ga naar kledingstukken</a>
            </div>
        </div>

        <!-- Klanten Kaart -->
        <div class="col-md-6">
            <div class="card card-custom">
                <h3 class="mb-3">Klanten</h3>
                <p class="text-muted">Beschrijving</p>
                <a href="klanten.php" class="btn btn-primary-custom mt-auto">Ga naar klanten</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
