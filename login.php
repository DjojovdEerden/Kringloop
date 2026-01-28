<?php

require_once __DIR__ . '/database/config/database.php';

// Sessie starten
session_start();

// Als gebruiker al ingelogd is, niets doen
if (isset($_SESSION['user_id'])) {
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
      $stmt = $pdo->prepare(query: "SELECT * FROM gebruiker WHERE gebruikersnaam = :username");
      $stmt->bindParam(param: ':username', var: $username);
      $stmt->execute();
      $user = $stmt->fetch(mode: PDO::FETCH_ASSOC);

      // Controleren of gebruiker bestaat en wachtwoord klopt
      if ($user && password_verify(password: $password, hash: $user['wachtwoord'])) {
        // session gegevens istellen
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['rollen'];
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


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Kringloop</title>
</head>
<body>

<header>
  <h1>Kringloop</h1>
</header>

<main>
  <h2>Login</h2>
  <?php if (isset($error)) { ?>
    <div class="error"><?php echo $error; ?></div>
  <?php } ?>
  <form method="post" action="#">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>
</main>

<footer>
</footer>

</body>
</html>




