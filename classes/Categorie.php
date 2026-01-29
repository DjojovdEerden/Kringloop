<?php
class Categorie {
    public $id;
    public $categorie;
    public $subcategorie;
    public $code;
    public $pdo;

    public function __construct($database) {
        $this->pdo = $database;
    }

    public function getAlleCategorien() {
        $query = "SELECT * FROM categorie ORDER BY categorie ASC, subcategorie ASC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function voegCategorieToe() {
        $query = "INSERT INTO categorie (categorie, subcategorie, code) 
                  VALUES (:categorie, :subcategorie, :code)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':categorie', $this->categorie);
        $stmt->bindParam(':subcategorie', $this->subcategorie);
        $stmt->bindParam(':code', $this->code);
        return $stmt->execute();
    }

    public function getCategorieNaam($id) {
        $query = "SELECT categorie, subcategorie FROM categorie WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result) {
            if($result['subcategorie']) {
                return $result['categorie'] . ' - ' . $result['subcategorie'];
            }
            return $result['categorie'];
        }
        return '';
    }
}
?>
