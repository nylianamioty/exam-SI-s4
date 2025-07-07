<?php
require_once __DIR__ . '/../db.php';

class Pret {
    public static function create($client_id, $type_pret_id,$fond_id, $amount, $start_date, $end_date, $status) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO pret (client_id, type_pret_id, fond_id, amount, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$client_id, $type_pret_id,$fond_id, $amount, $start_date, $end_date, $status]);
        return $db->lastInsertId();
    }

    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM pret");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($id, $data) {
        $db = getDB();

        $fields = [];
        $values = [];

        if (isset($data->client_id) && is_numeric($data->client_id)) {
            $fields[] = 'client_id = ?';
            $values[] = intval($data->client_id);
        }
        if (isset($data->type_pret_id) && is_numeric($data->type_pret_id)) {
            $fields[] = 'type_pret_id = ?';
            $values[] = intval($data->type_pret_id);
        }
        if (isset($data->amount) && is_numeric($data->amount)) {
            $fields[] = 'amount = ?';
            $values[] = floatval($data->amount);
        }
        if (isset($data->start_date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->start_date)) {
            $fields[] = 'start_date = ?';
            $values[] = $data->start_date;
        }
        if (isset($data->end_date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->end_date)) {
            $fields[] = 'end_date = ?';
            $values[] = $data->end_date;
        }
        if (isset($data->status)) {
            $fields[] = 'status = ?';
            $values[] = $data->status;
        }

        if (empty($fields)) {
            throw new Exception('Aucun champ valide à mettre à jour');
        }

        $values[] = intval($id);

        $stmt = $db->prepare("UPDATE pret SET " . implode(', ', $fields) . " WHERE id = ?");
        $stmt->execute($values);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM pret WHERE id = ?");
        $stmt->execute([intval($id)]);
    }
}
