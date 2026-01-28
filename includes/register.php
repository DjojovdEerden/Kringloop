<?php

include 'db_connect.php';

// variabelen 
$roles = [];
$message = '';

// haalt de rollen op uit de database van de rollen tabel
try {
    $rolesStmt = $pdo->query(query: "SELECT id, naam FROM roles ORDER BY naam");
    $roles = $rolesStmt->fetchAll(mode: PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Fout bij laden van rollen: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // haalt de input op van de gebruiker
    $gebruikersnaam = trim(string: $_POST['gebruikersnaam'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    $roleId = $_POST['role_id'] ?? '';

    // verificeert of alle velden zijn ingevuld 
    if ($gebruikersnaam === '' || $wachtwoord === '' || $roleId === '') {
        $message = "Vul alle velden in.";
    } else {
        // wachtwoord hashen 
        $hashedPassword = password_hash(password: $wachtwoord, algo: PASSWORD_BCRYPT);

        try {
            // controleer of gebruikersnaam al bestaat
            $checkStmt = $pdo->prepare(query: "SELECT 1 FROM gebruiker WHERE gebruikersnaam = ? LIMIT 1");
            $checkStmt->execute(params: [$gebruikersnaam]);
            if ($checkStmt->fetch()) {
                $message = "Gebruikersnaam bestaat al. Kies een andere.";
            } else {
                // haalt de rol op basis van de gegeven role_ID
                $roleStmt = $pdo->prepare(query: "SELECT naam FROM roles WHERE id = ?");
                $roleStmt->execute(params: [(int)$roleId]);
                $roleRow = $roleStmt->fetch(mode: PDO::FETCH_ASSOC);
                $roleNaam = $roleRow ? $roleRow['naam'] : '';

                // voorkomt dubbele gebruikersnamen 
                $idStmt = $pdo->query(query: "SELECT IFNULL(MAX(id), 0) + 1 AS next_id FROM gebruiker");
                $nextId = (int)$idStmt->fetchColumn();

                // voegt de nieuwe gebruiker toe aan de database in de gebruiker tabel 
                $stmt = $pdo->prepare(
                    query: "INSERT INTO gebruiker (id, gebruikersnaam, wachtwoord, role_id, rollen, is_geverifieerd)
                     VALUES (?, ?, ?, ?, ?, 0)"
                );
                $stmt->execute(params: [$nextId, $gebruikersnaam, $hashedPassword, (int)$roleId, $roleNaam]);

                $message = "Registratie gelukt!";
            }
        } catch (PDOException $e) {
            $message = "Fout: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registeren</title>
</head>
<body>
    <h1>Registeren</h1>

    <?php if ($message !== ''): ?>
        <p><?php echo htmlspecialchars(string: $message, flags: ENT_QUOTES, encoding: 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <div>
            <label for="gebruikersnaam">Gebruikersnaam</label>
            <input type="text" id="gebruikersnaam" name="gebruikersnaam" required>
        </div>

        <div>
            <label for="wachtwoord">Wachtwoord</label>
            <input type="password" id="wachtwoord" name="wachtwoord" required>
        </div>

        <div>
            <label for="role_id">Rol</label>
            <select id="role_id" name="role_id" required>
                <option value="">Kies een rol</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo (int)$role['id']; ?>">
                        <?php echo htmlspecialchars(string: $role['naam'], flags: ENT_QUOTES, encoding: 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Registreren</button>
    </form>
</body>
</html>