<?php
require_once 'config/db.php';
require_once 'classes/Categorie.php';

$foutmeldingen = [];
$succesbericht = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categorie_naam = trim($_POST['categorie']);
    $subcategorie_naam = trim($_POST['subcategorie']);
    $code = trim($_POST['code']);

    if(empty($categorie_naam)) {
        $foutmeldingen[] = 'Categorie naam is verplicht';
    }
    if(empty($code)) {
        $foutmeldingen[] = 'Code is verplicht';
    }

    if(count($foutmeldingen) == 0) {
        $categorieObj = new Categorie($db);
        $categorieObj->categorie = $categorie_naam;
        $categorieObj->subcategorie = !empty($subcategorie_naam) ? $subcategorie_naam : null;
        $categorieObj->code = $code;
        
        if($categorieObj->voegCategorieToe()) {
            if(isset($_POST['maak_aan_en_andere'])) {
                $succesbericht = 'Categorie succesvol toegevoegd! Je kan er nog een toevoegen.';
            } else {
                header('Location: artikelen.php');
                exit();
            }
        } else {
            $foutmeldingen[] = 'Er is iets misgegaan bij het toevoegen. Mogelijk bestaat deze code al.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorie Toevoegen</title>
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
        .succesbericht {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
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
        .knop-secundair {
            background-color: #28a745;
        }
        .knop-secundair:hover {
            background-color: #218838;
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
        .form-hint {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nieuwe Categorie Toevoegen</h1>

        <?php if(count($foutmeldingen) > 0): ?>
            <div class="foutmelding">
                <?php foreach($foutmeldingen as $fout): ?>
                    <p><?php echo $fout; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if($succesbericht != ''): ?>
            <div class="succesbericht"><?php echo $succesbericht; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-groep">
                <label for="categorie">Categorie Naam: *</label>
                <input type="text" id="categorie" name="categorie" 
                       placeholder="Bijv. Kleding, Meubels, Witgoed"
                       value="<?php echo isset($_POST['categorie']) && !$succesbericht ? htmlspecialchars($_POST['categorie']) : ''; ?>">
                <div class="form-hint">Hoofdcategorie naam</div>
            </div>

            <div class="form-groep">
                <label for="subcategorie">Subcategorie:</label>
                <input type="text" id="subcategorie" name="subcategorie" 
                       placeholder="Bijv. Dameskleding, Eettafels, Wasmachines"
                       value="<?php echo isset($_POST['subcategorie']) && !$succesbericht ? htmlspecialchars($_POST['subcategorie']) : ''; ?>">
                <div class="form-hint">Optioneel - laat leeg als niet van toepassing</div>
            </div>

            <div class="form-groep">
                <label for="code">Code: *</label>
                <input type="text" id="code" name="code" 
                       placeholder="Bijv. KL-DA, ME-ET, WG-WA"
                       value="<?php echo isset($_POST['code']) && !$succesbericht ? htmlspecialchars($_POST['code']) : ''; ?>">
                <div class="form-hint">Unieke code voor deze categorie</div>
            </div>

            <button type="submit" name="maak_aan" class="knop">Maak aan</button>
            <button type="submit" name="maak_aan_en_andere" class="knop knop-secundair">Maak aan en voeg andere toe</button>
            <a href="artikelen.php" class="terug-knop">Terug</a>
        </form>
    </div>
</body>
</html>
