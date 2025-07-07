<?php
Flight::route('POST /fonds', function() {
    error_log('Requête POST /fonds reçue');
    $data = Flight::request()->data;
    error_log('Données reçues : ' . json_encode($data));

    // Vérifier les données
    if (!isset($data->montant) || !is_numeric($data->montant)) {
        error_log('Erreur : montant manquant ou non numérique');
        Flight::json(['error' => 'montant manquant ou non numérique'], 400);
        return;
    }

    $description = isset($data->description) ? $data->description : null;

    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO fonds (description, montant, date_ajout) VALUES (?, ?, NOW())");
        $stmt->execute([$description, $data->montant]);
        $response = ['message' => 'Fond ajouté avec succès', 'id' => $db->lastInsertId()];
        error_log('Réponse envoyée : ' . json_encode($response));
        Flight::json($response);
    } catch (Exception $e) {
        error_log('Erreur lors de l\'insertion : ' . $e->getMessage());
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('GET /fonds', function() {
    error_log('Requête GET /fonds reçue');
    try {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM fonds");
        $fonds = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log('Fonds récupérés : ' . json_encode($fonds));
        Flight::json($fonds);
    } catch (Exception $e) {
        error_log('Erreur lors de la récupération : ' . $e->getMessage());
        Flight::json(['error' => $e->getMessage()], 500);
    }
});
