<?php

    class PredictionModel {
        const PREDICTION_WON = 1;
        const PREDICTION_LOST = 0;
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

        public static function getPredictions($pdoConnection, $limit, $offset, $query, $isOddsQuery) {
            try {
                $sql = "SELECT (SELECT COUNT(*) FROM predictions WHERE predictions.approved = 0) AS total, 
                     (SELECT COUNT(*) FROM comments WHERE predictions.id=comments.id) AS total_comments,
                    predictions.prediction, predictions.id, predictions.created_at, predictions.start_date, 
                    predictions.end_date, predictions.won, users.id AS user_id, users.name, users.sex, users.image_path
                    FROM predictions 
                    INNER JOIN users ON predictions.user_id = users.id 
                    WHERE predictions.approved = 0" . self::getQueryPredictionSQL($query, $isOddsQuery) . 
                    " ORDER BY predictions.start_date desc
                    LIMIT " . $limit . " OFFSET " . $offset . ";";

                  // echo $sql; exit;
                return $pdoConnection->pdo->query($sql)->fetchAll();

            } catch(Exception $e) {
                var_dump($e->getMessage()); exit;
                return false;
            }  
            
        }

        public static function getQueryPredictionSQL($query, $isOddsQuery) {
            // var_dump($query); exit;
            if (!$query)
                return '';

            if ($isOddsQuery) {
                $minMAx = explode('_', $query);
                return ' AND predictions.total_odds >=' . $minMAx[0] . ' AND predictions.total_odds <=' . $minMAx[1]; 
            }

            if ((int)$query == HomeController::INPROGRESS_PREDICTION_QUERY) {
                return ' AND predictions.start_date < ' . "'" . gmdate("Y-m-d\ H:i:s") . "'" . " AND predictions.won IS NULL";
            }

            $won = (int)$query == HomeController::CORRECT_PREDICTION_QUERY ? self::PREDICTION_WON : self::PREDICTION_LOST;
            return ' AND predictions.won=' . $won;
        }
    }

?>