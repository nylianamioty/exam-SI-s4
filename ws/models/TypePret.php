<?php
require_once __DIR__ . '/../db.php';

class TypePret {
    public static function create($nom, $taux) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO type_pret (nom, taux) VALUES (?, ?)");
        $stmt->execute([$nom, $taux]);
        return $db->lastInsertId();
    }

    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM type_pret");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
