<?php

require_once 'includes/db.php';

// Sessie starten
session_start();

// Als gebruiker al ingelogd is, doorsturen naar home
if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

// Verwerking van het loginformulier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Invoer ophalen
  $username = trim(string: $_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '' || $password === '') {
    // loging foutmelding
    $error = "Invalid username or password";
  } else {
    try {
      // Gebruiker ophalen met opgegeven gebruikersnaam
      $stmt = $pdo->prepare("SELECT g.*, r.naam as rol_naam FROM gebruiker g LEFT JOIN rollen r ON g.rol_id = r.id WHERE g.gebruikersnaam = :username");
      $stmt->bindParam(':username', $username);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Controleren of gebruiker bestaat en wachtwoord klopt
      if ($user && password_verify($password, $user['wachtwoord'])) {
        // session gegevens istellen
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['rol_id'] = $user['rol_id'];
        $_SESSION['username'] = $user['gebruikersnaam'] ?? null;
        $_SESSION['role'] = $user['rollen'] ?? null;
        header('Location: index.php');
        exit;
      } else {
        if ($user) {
          $error = "Incorrect password";
        } else {
          $error = "Username not found";
        }
      }
    } catch(PDOException $e) {
      // database foutmelding geen connectie 
      $error = "Connection failed: " . $e->getMessage();
    }
  }
}

?>

<?php
$pageTitle = 'Inloggen';
include 'includes/header.php';
?>

<div class="container my-5" style="max-width: 480px;">
  <h2 class="mb-4">Login</h2>
  <?php if (isset($error)) { ?>
    <div class="alert alert-danger" role="alert">
      <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
    </div>
  <?php } ?>
  <form method="post" action="">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" id="username" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary-custom w-100">Login</button>
  </form>
</div>

<?php include 'includes/footer.php'; ?>




