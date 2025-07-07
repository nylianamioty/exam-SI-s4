<?php
require_once __DIR__ . '/../models/Pret.php';

class PretController {
    public static function create() {
        $data = Flight::request()->data;

        if (!isset($data->client_id) || !is_numeric($data->client_id)) {
            Flight::json(['error' => 'client_id est requis et doit être un nombre'], 400);
            return;
        }
        if (!isset($data->type_pret_id) || !is_numeric($data->type_pret_id)) {
            Flight::json(['error' => 'type_pret_id est requis et doit être un nombre'], 400);
            return;
        }
        if (!isset($data->fond_id) || !is_numeric($data->fond_id)) {
            Flight::json(['error' => 'fond_id est requis et doit être un nombre'], 400);
            return;
        }
        if (!isset($data->amount) || !is_numeric($data->amount)) {
            Flight::json(['error' => 'amount est requis et doit être un nombre'], 400);
            return;
        }
        if (!isset($data->start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->start_date)) {
            Flight::json(['error' => 'start_date est requis et doit être au format YYYY-MM-DD'], 400);
            return;
        }

        $client_id = intval($data->client_id);
        $type_pret_id = intval($data->type_pret_id);
        $fond_id = intval($data->fond_id);
        $amount = floatval($data->amount);
        $start_date = $data->start_date;
        $end_date = isset($data->end_date) ? $data->end_date : null;
        $status = isset($data->status) ? $data->status : 'active';

        try {
            $id = Pret::create($client_id, $type_pret_id, $fond_id, $amount, $start_date, $end_date, $status);
            Flight::json(['message' => 'Prêt client ajouté avec succès', 'id' => $id]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public static function getAll() {
        try {
            $pret = Pret::getAll();
            Flight::json($pret);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public static function update($id) {
        $data = Flight::request()->data;

        try {
            Pret::update($id, $data);
            Flight::json(['message' => 'Prêt client mis à jour avec succès']);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public static function delete($id) {
        try {
            Pret::delete($id);
            Flight::json(['message' => 'Prêt client supprimé avec succès']);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}
