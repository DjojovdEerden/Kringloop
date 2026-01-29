<?php
require_once 'config/db.php';
require_once 'classes/Artikel.php';
require_once 'classes/Categorie.php';

$artikel = new Artikel($db);
$categorieObj = new Categorie($db);
$categorien = $categorieObj->getAlleCategorien();

$zoekterm = isset($_GET['zoekterm']) ? trim($_GET['zoekterm']) : '';
$categorie_filter = isset($_GET['categorie']) ? trim($_GET['categorie']) : '';

if(!empty($zoekterm) || !empty($categorie_filter)) {
    $artikelen = $artikel->zoekArtikelen($zoekterm, $categorie_filter);
} else {
    $artikelen = $artikel->getAlleArtikelen();
}

$bericht = '';
if(isset($_GET['verwijderd']) && $_GET['verwijderd'] == 'success') {
    $bericht = 'Artikel succesvol verwijderd!';
}
if(isset($_GET['toegevoegd']) && $_GET['toegevoegd'] == 'success') {
    $bericht = 'Artikel succesvol toegevoegd!';
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikelen Overzicht</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
        h1 {
            color: #333;
        }
        .bericht {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .knop-container {
            margin-bottom: 20px;
        }
        .knop {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .knop:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }
        th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .verwijder-knop {
            background-color: #dc3545;
            color: white;
            padding: 5px 15px;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .verwijder-knop:hover {
            background-color: #c82333;
        }
        .zoek-container {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .zoek-form {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        .zoek-veld {
            flex: 1;
        }
        .zoek-veld label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .zoek-veld input,
        .zoek-veld select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .zoek-knop {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .zoek-knop:hover {
            background-color: #218838;
        }
        .reset-knop {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .reset-knop:hover {
            background-color: #5a6268;
        }
        .categorie-badge {
            display: inline-block;
            padding: 4px 10px;
            background-color: #3366CC;
            color: white;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 600;
        }
        .subcategorie-text {
            display: block;
            font-size: 0.85em;
            color: #6c757d;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Artikelen Overzicht</h1>
        
        <?php if($bericht != ''): ?>
            <div class="bericht"><?php echo $bericht; ?></div>
        <?php endif; ?>

        <div class="zoek-container">
            <form method="GET" class="zoek-form">
                <div class="zoek-veld">
                    <label for="zoekterm">Zoek op naam:</label>
                    <input type="text" id="zoekterm" name="zoekterm" 
                           placeholder="Bijv. tafel, stoel..." 
                           value="<?php echo htmlspecialchars($zoekterm); ?>">
                </div>
                
                <div class="zoek-veld">
                    <label for="categorie">Filter op categorie:</label>
                    <select id="categorie" name="categorie">
                        <option value="">-- Alle categorieën --</option>
                        <?php foreach($categorien as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"
                                    <?php echo ($categorie_filter == $cat['id']) ? 'selected' : ''; ?>>
                                <?php 
                                    echo htmlspecialchars($cat['categorie']);
                                    if($cat['subcategorie']) {
                                        echo ' - ' . htmlspecialchars($cat['subcategorie']);
                                    }
                                    echo ' (' . htmlspecialchars($cat['code']) . ')';
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="zoek-knop">Zoeken</button>
                <a href="artikelen.php" class="reset-knop">Reset</a>
            </form>
        </div>

        <div class="knop-container">
            <a href="artikel_toevoegen.php" class="knop">+ Nieuw Artikel</a>
            <a href="categorie_toevoegen.php" class="knop">+ Nieuwe Categorie</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 45%;">Naam</th>
                    <th style="width: 25%;">Categorie</th>
                    <th style="width: 12%;">Prijs (ex. BTW)</th>
                    <th style="width: 10%;">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($artikelen) > 0): ?>
                    <?php foreach($artikelen as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($item['naam']); ?></strong></td>
                            <td>
                                <span class="categorie-badge">
                                    <?php echo htmlspecialchars($item['categorie']); ?>
                                </span>
                                <?php if($item['subcategorie']): ?>
                                    <span class="subcategorie-text">
                                        → <?php echo htmlspecialchars($item['subcategorie']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>&euro; <?php echo number_format($item['prijs_ex_btw'], 2, ',', '.'); ?></td>
                            <td>
                                <a href="artikel_verwijderen.php?id=<?php echo $item['id']; ?>" 
                                   class="verwijder-knop"
                                   onclick="return confirm('Weet je zeker dat je dit artikel wilt verwijderen?');">
                                    Verwijderen
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px;">
                            Geen artikelen gevonden
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
