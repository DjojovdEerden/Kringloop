<?php
// Database connectie laden
require_once '../includes/db.php';

// Check of ID is meegegeven
if (!isset($_GET['id'])) {
    header('Location: list_voorraad.php');
    exit;
}

$voorraad_id = (int)$_GET['id'];

try {
    // Voorraad item verwijderen uit database
    $stmt = $pdo->prepare("DELETE FROM voorraad WHERE id = ?");
    $stmt->execute([$voorraad_id]);
    
    // Redirect naar lijst
    header('Location: list_voorraad.php?deleted=1');
    exit;
    
} catch (PDOException $e) {
    // Error bij verwijderen - redirect met error
    header('Location: list_voorraad.php?error=delete_failed');
    exit;
}
?>