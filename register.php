<?php

require_once 'includes/db.php';

// Sessie starten
session_start();

// Alleen rol_id 1 mag registeren
if (!isset($_SESSION['user_id']) || (int)($_SESSION['rol_id'] ?? 0) !== 1) {
    header('Location: login.php');
    exit;
}

// variabelen 
$roles = [];
$message = '';

// haalt de rollen op uit de database van de rollen tabel
try {
    $rolesStmt = $pdo->query(query: "SELECT id, naam FROM rollen ORDER BY naam");
    $roles = $rolesStmt->fetchAll(mode: PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Fout bij laden van rollen: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // haalt de input op van de gebruiker
    $gebruikersnaam = trim(string: $_POST['gebruikersnaam'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    $roleId = $_POST['rol_id'] ?? '';

    // verificeert of alle velden zijn ingevuld 
    if ($gebruikersnaam === '' || $wachtwoord === '' || $roleId === '') {
        $message = "Vul alle velden in.";
    } else {
        // wachtwoord hashen 
        $hashedPassword = password_hash(password: $wachtwoord, algo: PASSWORD_BCRYPT);

        try {
            // controleer of gebruikersnaam al bestaat
            $checkStmt = $pdo->prepare("SELECT 1 FROM gebruiker WHERE gebruikersnaam = ? LIMIT 1");
            $checkStmt->execute(params: [$gebruikersnaam]);
            if ($checkStmt->fetch()) {
                $message = "Gebruikersnaam bestaat al. Kies een andere.";
            } else {
                // voorkomt dubbele gebruikersnamen 
                $idStmt = $pdo->query("SELECT IFNULL(MAX(id), 0) + 1 AS next_id FROM gebruiker");
                $nextId = (int)$idStmt->fetchColumn();

                // voegt de nieuwe gebruiker toe aan de database in de gebruiker tabel 
                $stmt = $pdo->prepare(
                    query: "INSERT INTO gebruiker (id, gebruikersnaam, wachtwoord, rol_id, is_geverifieerd)
                     VALUES (?, ?, ?, ?, 0)"
                );
                 $stmt->execute(params: [$nextId, $gebruikersnaam, $hashedPassword, (int)$roleId]);

                $message = "Registratie gelukt!";
            }
        } catch (PDOException $e) {
            $message = "Fout: " . $e->getMessage();
        }
    }
}

?>

<?php
$pageTitle = 'Registreren';
include 'includes/header.php';
?>

<div class="container my-5" style="max-width: 640px;">
    <h1 class="mb-4">Registeren</h1>

    <?php if ($message !== ''): ?>
        <div class="alert alert-info" role="alert">
            <?php echo htmlspecialchars(string: $message, flags: ENT_QUOTES, encoding: 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-3">
            <label for="gebruikersnaam" class="form-label">Gebruikersnaam</label>
            <input type="text" id="gebruikersnaam" name="gebruikersnaam" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="wachtwoord" class="form-label">Wachtwoord</label>
            <input type="password" id="wachtwoord" name="wachtwoord" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="rol_id" class="form-label">Rol</label>
            <select id="rol_id" name="rol_id" class="form-select" required>
                <option value="">Kies een rol</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo (int)$role['id']; ?>">
                        <?php echo htmlspecialchars(string: $role['naam'], flags: ENT_QUOTES, encoding: 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary-custom">Registreren</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>