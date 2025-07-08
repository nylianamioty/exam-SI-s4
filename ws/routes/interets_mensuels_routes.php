<?php 
Flight::route('GET /interets_mensuels', function() {
    $debut = Flight::request()->query['start'] ?? null;
    $fin = Flight::request()->query['end'] ?? null;

    try {
        $db = getDB();
        // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $query = "SELECT * FROM interets_mensuels WHERE 1=1";
        $params = [];
        
        if ($debut) {
            $query .= " AND periode >= ?";
            $params[] = $debut;
        }
        
        if ($fin) {
            $query .= " AND periode <= ?";
            $params[] = $fin;
        }
        
        $query .= " ORDER BY periode";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $donneesGraphique = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Intérêts mensuels',
                    'data' => [],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)'
                ]
            ]
        ];
        
        foreach ($resultats as $ligne) {
            $donneesGraphique['labels'][] = $ligne['periode'];
            $donneesGraphique['datasets'][0]['data'][] = (float)$ligne['interet_mensuel'];
        }
        
        Flight::json([
            'success' => true,
            'table_data' => $resultats ?: [],
            'chart_data' => $donneesGraphique
        ]);
        
    } catch (PDOException $e) {
        Flight::json([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ], 500);
    } catch (Exception $e) {
        Flight::json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});