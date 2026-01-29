<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Kringloop centrum'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Kringloop/assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="/Kringloop/index.php">
                <img src="/Kringloop/assets/img/Logo Kringloop.png" alt="Logo" class="navbar-logo me-2">
                Kringloop centrum
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/Kringloop/index.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="rittenDropdown" role="button" data-bs-toggle="dropdown">Ritten</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/Kringloop/admin/add_planning.php">Nieuwe Planning</a></li>
                            <li><a class="dropdown-item" href="/Kringloop/admin/list_planning.php">Planning Overzicht</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Personen</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/Kringloop/admin/add_persoon.php">Nieuwe Persoon</a></li>
                            <li><a class="dropdown-item" href="/Kringloop/admin/list_personen.php">Personen Overzicht</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Voorraad</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/Kringloop/admin/add_voorraad.php">Nieuwe Voorraad</a></li>
                            <li><a class="dropdown-item" href="/Kringloop/admin/list_voorraad.php">Voorraad Overzicht</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/Kringloop/voorraad.php">Voorraad</a></li>
                    <li class="nav-item"><a class="nav-link" href="/Kringloop/beheer.php">Beheer</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Admin</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="">Dashboard</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="nav-link text-white">
                                <?php echo htmlspecialchars($_SESSION['username'] ?? 'Gebruiker', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light ms-2" href="logout.php">Uitloggen</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light" href="login.php">Aanmelden</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
