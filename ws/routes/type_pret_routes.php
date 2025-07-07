<?php
Flight::route('POST /type_pret', function() {
    $data = Flight::request()->data;

    // Validate input
    if (!isset($data->nom) || !is_string($data->nom) || empty(trim($data->nom))) {
        Flight::json(['error' => 'Le nom du type de prêt est requis'], 400);
        return;
    }
    if (!isset($data->taux) || !is_numeric($data->taux)) {
        Flight::json(['error' => 'Le taux d\'intérêt est requis et doit être un nombre'], 400);
        return;
    }

    $nom = trim($data->nom);
    $taux = floatval($data->taux);

    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO type_pret (nom, taux) VALUES (?, ?)");
        $stmt->execute([$nom, $taux]);
        $response = ['message' => 'Type de prêt ajouté avec succès', 'id' => $db->lastInsertId()];
        Flight::json($response);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('GET /type_pret', function() {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM type_pret");
        $type_pret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Flight::json($type_pret);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});
