<?php
require_once 'config/db.php';
require_once 'classes/Artikel.php';
require_once 'classes/Categorie.php';

$foutmeldingen = [];
$categorieObj = new Categorie($db);
$categorien = $categorieObj->getAlleCategorien();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $naam = trim($_POST['naam']);
    $categorie_id = trim($_POST['categorie_id']);
    $prijs = trim($_POST['prijs']);

    if(empty($naam)) {
        $foutmeldingen[] = 'Naam is verplicht';
    }
    if(empty($categorie_id)) {
        $foutmeldingen[] = 'Selecteer een categorie';
    }
    if(empty($prijs)) {
        $foutmeldingen[] = 'Prijs is verplicht';
    }
    if(!is_numeric($prijs)) {
        $foutmeldingen[] = 'Prijs moet een getal zijn';
    }

    if(count($foutmeldingen) == 0) {
        $artikel = new Artikel($db);
        $artikel->naam = $naam;
        $artikel->categorie_id = $categorie_id;
        $artikel->prijs_ex_btw = $prijs;
        
        if($artikel->voegArtikelToe()) {
            header('Location: artikelen.php?toegevoegd=success');
            exit();
        } else {
            $foutmeldingen[] = 'Er is iets misgegaan bij het toevoegen';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel Toevoegen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
        h1 {
            color: #333;
        }
        .foutmelding {
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .form-groep {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            min-height: 80px;
            font-family: Arial, sans-serif;
        }
        .knop {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .knop:hover {
            background-color: #0056b3;
        }
        .terug-knop {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .terug-knop:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nieuw Artikel Toevoegen</h1>

        <?php if(count($foutmeldingen) > 0): ?>
            <div class="foutmelding">
                <?php foreach($foutmeldingen as $fout): ?>
                    <p><?php echo $fout; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-groep">
                <label for="naam">Naam: *</label>
                <input type="text" id="naam" name="naam" 
                       value="<?php echo isset($_POST['naam']) ? htmlspecialchars($_POST['naam']) : ''; ?>">
            </div>

            <div class="form-groep">
                <label for="categorie_id">Categorie: *</label>
                <select id="categorie_id" name="categorie_id">
                    <option value="">-- Selecteer categorie --</option>
                    <?php foreach($categorien as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"
                                <?php echo (isset($_POST['categorie_id']) && $_POST['categorie_id'] == $cat['id']) ? 'selected' : ''; ?>>
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

            <div class="form-groep">
                <label for="prijs">Prijs (ex. BTW): *</label>
                <input type="number" id="prijs" name="prijs" step="0.01" 
                       value="<?php echo isset($_POST['prijs']) ? htmlspecialchars($_POST['prijs']) : ''; ?>">
                <div style="background-color: #e7f3ff; padding: 10px; border-radius: 4px; margin-top: 10px; font-size: 0.9em;" id="btw-info"></div>
            </div>

            <button type="submit" class="knop">Toevoegen</button>
            <a href="artikelen.php" class="terug-knop">Terug</a>
        </form>
    </div>

    <script>
        function berekenBTW() {
            const exBTW = parseFloat(document.getElementById('prijs').value) || 0;
            const incBTW = exBTW * 1.21;
            document.getElementById('btw-info').textContent = 
                `Prijs inc. 21% BTW: € ${incBTW.toFixed(2).replace('.', ',')}`;
        }
        
        document.getElementById('prijs').addEventListener('input', berekenBTW);
    </script>
</body>
</html>
