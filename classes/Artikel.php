<?php
class Artikel {
    public $id;
    public $categorie_id;
    public $naam;
    public $prijs_ex_btw;
    public $pdo;

    public function __construct($database) {
        $this->pdo = $database;
    }

    public function getAlleArtikelen() {
        $query = "SELECT artikel.*, categorie.categorie, categorie.subcategorie, categorie.code
                  FROM artikel 
                  LEFT JOIN categorie ON artikel.categorie_id = categorie.id 
                  ORDER BY artikel.id DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function voegArtikelToe() {
        $query = "INSERT INTO artikel (categorie_id, naam, prijs_ex_btw) 
                  VALUES (:categorie_id, :naam, :prijs_ex_btw)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':categorie_id', $this->categorie_id);
        $stmt->bindParam(':naam', $this->naam);
        $stmt->bindParam(':prijs_ex_btw', $this->prijs_ex_btw);
        return $stmt->execute();
    }

    public function verwijderArtikel($id) {
        // Verwijder eerst gerelateerde voorraad records
        $queryVoorraad = "DELETE FROM voorraad WHERE artikel_id = :id";
        $stmtVoorraad = $this->pdo->prepare($queryVoorraad);
        $stmtVoorraad->bindParam(':id', $id);
        $stmtVoorraad->execute();
        
        // Verwijder het artikel
        $query = "DELETE FROM artikel WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function zoekArtikelen($zoekterm, $categorie_id) {
        $query = "SELECT artikel.*, categorie.categorie, categorie.subcategorie, categorie.code
                  FROM artikel 
                  LEFT JOIN categorie ON artikel.categorie_id = categorie.id 
                  WHERE 1=1";
        
        if(!empty($zoekterm)) {
            $query .= " AND artikel.naam LIKE :zoekterm";
        }
        
        if(!empty($categorie_id)) {
            $query .= " AND artikel.categorie_id = :categorie_id";
        }
        
        $query .= " ORDER BY artikel.id DESC";
        
        $stmt = $this->pdo->prepare($query);
        
        if(!empty($zoekterm)) {
            $zoekterm_param = '%' . $zoekterm . '%';
            $stmt->bindParam(':zoekterm', $zoekterm_param);
        }
        
        if(!empty($categorie_id)) {
            $stmt->bindParam(':categorie_id', $categorie_id);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
