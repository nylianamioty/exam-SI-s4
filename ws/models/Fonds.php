<?php
require_once __DIR__ . '/../db.php';

class Fonds {
    public static function create($description, $valeur) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO fonds (description, montant, date_ajout) VALUES (?, ?, NOW())");
        $stmt->execute([$description, $valeur]);
        return $db->lastInsertId();
    }

    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM fonds");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
