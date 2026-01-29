<?php
// Database connectie laden
require_once '../includes/db.php';

// Sessie starten
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Alleen rol_id 1 mag beheren
if (!isset($_SESSION['user_id']) || (int)($_SESSION['rol_id'] ?? 0) !== 1) {
    header('Location: /Kringloop/login.php');
    exit;
}

// Feedbackbericht voor acties
$message = '';

// Rollen ophalen voor dropdown in de tabel
$rolesStmt = $pdo->query('SELECT id, naam FROM rollen ORDER BY naam');
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);

// Verwerking van acties (bewerk, blokkeer, deblokkeer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetUserId = (int)($_POST['user_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($targetUserId > 0) {
        // Gebruiker bewerken (gebruikersnaam/rol)
        if ($action === 'edit') {
            $newUsername = trim($_POST['gebruikersnaam'] ?? '');
            $newRoleId = (int)($_POST['rol_id'] ?? 0);

            // Validatie van invoer
            if ($newUsername === '' || $newRoleId === 0) {
                $message = 'Vul een gebruikersnaam en rol in.';
            } else {
                // Unieke gebruikersnaam check
                $checkStmt = $pdo->prepare('SELECT 1 FROM gebruiker WHERE gebruikersnaam = ? AND id <> ? LIMIT 1');
                $checkStmt->execute([$newUsername, $targetUserId]);

                if ($checkStmt->fetch()) {
                    $message = 'Gebruikersnaam bestaat al. Kies een andere.';
                } else {
                    // Update uitvoeren
                    $updateStmt = $pdo->prepare('UPDATE gebruiker SET gebruikersnaam = :username, rol_id = :rol_id WHERE id = :id');
                    $updateStmt->execute([
                        ':username' => $newUsername,
                        ':rol_id' => $newRoleId,
                        ':id' => $targetUserId,
                    ]);
                    $message = 'Gebruiker bijgewerkt.';
                }
            }
        } else {
            // Blokkeren/deblokkeren (niet voor eigen account)
            if ($targetUserId === (int)($_SESSION['user_id'] ?? 0)) {
                $message = 'Je kunt je eigen account niet blokkeren.';
            } else {
                $newStatus = ($action === 'block') ? 1 : 0;
                $stmt = $pdo->prepare('UPDATE gebruiker SET is_geverifieerd = :status WHERE id = :id');
                $stmt->execute([
                    ':status' => $newStatus,
                    ':id' => $targetUserId,
                ]);
                $message = $newStatus === 1 ? 'Gebruiker geblokkeerd.' : 'Gebruiker gedeblokkeerd.';
            }
        }
    }
}

// Alle gebruikers + rolnaam ophalen voor overzicht
$stmt = $pdo->query('SELECT g.id, g.gebruikersnaam, g.rol_id, g.is_geverifieerd, r.naam AS rol_naam FROM gebruiker g LEFT JOIN rollen r ON g.rol_id = r.id ORDER BY g.id');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$pageTitle = 'Gebruikers beheren';
include '../includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Gebruikers beheren</h2>

    <?php if ($message !== ''): ?>
        <div class="alert alert-info" role="alert">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Gebruikersnaam</th>
                        <th>Rol</th>
                        <th>Status</th>
                        <th>Actie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo (int)$user['id']; ?></td>
                            <td>
                                <form method="post" action="" class="d-flex gap-2 align-items-center flex-wrap">
                                    <input type="hidden" name="user_id" value="<?php echo (int)$user['id']; ?>">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="text" name="gebruikersnaam" class="form-control form-control-sm" value="<?php echo htmlspecialchars($user['gebruikersnaam'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" style="min-width: 180px;">
                            </td>
                            <td>
                                    <select name="rol_id" class="form-select form-select-sm" style="min-width: 180px;">
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?php echo (int)$role['id']; ?>" <?php echo ((int)$role['id'] === (int)$user['rol_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($role['naam'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                            </td>
                            <td>
                                <?php if ((int)$user['is_geverifieerd'] === 1): ?>
                                    <span class="badge bg-danger">Geblokkeerd</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Actief</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-sm btn-primary">Opslaan</button>
                                    </form>
                                    <?php if ((int)$user['id'] === (int)($_SESSION['user_id'] ?? 0)): ?>
                                        <button class="btn btn-sm btn-secondary" disabled>Eigen account</button>
                                    <?php else: ?>
                                        <form method="post" action="" class="d-inline">
                                            <input type="hidden" name="user_id" value="<?php echo (int)$user['id']; ?>">
                                            <?php if ((int)$user['is_geverifieerd'] === 1): ?>
                                                <input type="hidden" name="action" value="unblock">
                                                <button type="submit" class="btn btn-sm btn-success">Deblokkeer</button>
                                            <?php else: ?>
                                                <input type="hidden" name="action" value="block">
                                                <button type="submit" class="btn btn-sm btn-danger">Blokkeer</button>
                                            <?php endif; ?>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
