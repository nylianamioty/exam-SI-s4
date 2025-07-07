<?php
require_once __DIR__ . '/../models/TypePret.php';

class TypePretController {
    public static function create() {
        $data = Flight::request()->data;

        if (!isset($data->name) || !is_string($data->name) || empty(trim($data->name))) {
            Flight::json(['error' => 'Le nom du type de prêt est requis'], 400);
            return;
        }
        if (!isset($data->taux) || !is_numeric($data->taux)) {
            Flight::json(['error' => 'Le taux d\'intérêt est requis et doit être un nombre'], 400);
            return;
        }

        $name = trim($data->name);
        $taux = floatval($data->taux);

        try {
            $id = TypePret::create($name, $taux);
            Flight::json(['message' => 'Type de prêt ajouté avec succès', 'id' => $id]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public static function getAll() {
        try {
            $type_pret = TypePret::getAll();
            Flight::json($type_pret);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}
