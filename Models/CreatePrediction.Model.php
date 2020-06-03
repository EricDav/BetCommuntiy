<?php

    class CreatePredictionModel {
        public static function createPrediction($pdoConnection, $startDate, $endDate, $userId, $odds, $prediction, $approved) {
            // var_dump($approved); exit;
            try {
                $sql = 'INSERT INTO predictions (start_date, end_date, prediction, total_odds, user_id, approved, created_at) VALUES(?,?,?,?,?,?,?)';
                $stmt= $pdoConnection->pdo->prepare($sql);
                return $stmt->execute([$startDate, $endDate, $prediction, $odds, $userId, $approved, gmdate("Y-m-d\ H:i:s")]);
            } catch(Exception $e) {
                var_dump($e->getMessage()); exit;
                return false;
            }
        }

        public static function getPredictions($pdoConnection, $limit, $offset) {
            try {
                $sql = "SELECT (SELECT COUNT(*) FROM predictions 
                    WHERE predictions.approved = 1) AS total, 
                    predictions.prediction, predictions.id, predictions.created_at, predictions.start_date, 
                    predictions.end_date, users.id AS user_id, users.name, users.sex
                    FROM predictions 
                    INNER JOIN users ON predictions.user_id = users.id 
                    WHERE predictions.approved = 0 
                    ORDER BY predictions.start_date desc
                    LIMIT " . $limit . " OFFSET " . $offset . ";";
                return $pdoConnection->pdo->query($sql)->fetchAll();

            } catch(Exception $e) {
                var_dump($e->getMessage()); exit;
                return false;
            }  
            
        }
    }

?>