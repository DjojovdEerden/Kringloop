<?php
require_once 'includes/db.php';
require_once 'classes/Artikel.php';

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $artikel = new Artikel($pdo);
    if($artikel->verwijderArtikel($_GET['id'])) {
        header('Location: artikelen.php?verwijderd=success');
        exit();
    }
}

header('Location: artikelen.php');
exit();
?>
