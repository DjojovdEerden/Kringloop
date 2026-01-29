<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = 'Kringloop centrum - Home';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row g-4">
        <!-- Ritten Kaart -->
        <div class="col-md-6">
            <div class="card card-custom">
                <h3 class="mb-3">Ritten</h3>
                <p class="text-muted">Plan ophaal- en bezorgritten</p>
                <a href="admin/list_planning.php" class="btn btn-primary-custom mt-auto">Ga naar ritten planning</a>
            </div>
        </div>

        <!-- Voorraadbeheer Kaart -->
        <div class="col-md-6">
            <div class="card card-custom">
                <h3 class="mb-3">Voorraad beheer</h3>
                <p class="text-muted">Beheer magazijn en winkelvoorraad</p>
                <a href="admin/list_voorraad.php" class="btn btn-primary-custom mt-auto">Ga naar voorraad beheer</a>
            </div>
        </div>

        <!-- Artikelen Kaart -->
        <div class="col-md-6">
            <div class="card card-custom">
                <h3 class="mb-3">Artikelen</h3>
                <p class="text-muted">Beheer alle artikelen en categorieën</p>
                <a href="artikelen.php" class="btn btn-primary-custom mt-auto">Ga naar artikelen beheer</a>
            </div>
        </div>

        <!-- Personen Kaart -->
        <div class="col-md-6">
            <div class="card card-custom">
                <h3 class="mb-3">Personen</h3>
                <p class="text-muted">Beheer klanten en leveranciers</p>
                <a href="admin/list_personen.php" class="btn btn-primary-custom mt-auto">Ga naar personen beheer</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
