<?php
require_once __DIR__ . '/../models/Fonds.php';

class FondsController {
    public static function create() {
        $data = Flight::request()->data;

        if (!isset($data->valeur) || !is_numeric($data->valeur)) {
            Flight::json(['error' => 'Valeur manquante ou non numérique'], 400);
            return;
        }

        $description = isset($data->description) ? $data->description : null;

        try {
            $id = Fonds::create($description, $data->valeur);
            Flight::json(['message' => 'Fond ajouté avec succès', 'id' => $id]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public static function getAll() {
        try {
            $fonds = Fonds::getAll();
            Flight::json($fonds);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}
