<?php

    class PredictionModel {
        const PREDICTION_WON = 1;
        const PREDICTION_LOST = 0;
        const APPROVED = 1;
        const UN_APPROVED = 0;
        const DEFAULT_SCROES_FINISHED = 0;
        
        public static function createPrediction($pdoConnection, $startDate, $endDate, $userId, $odds, $prediction, $approved, $type) {
            // var_dump($approved); exit;
            try {
                $sql = 'INSERT INTO predictions (start_date, end_date, prediction, total_odds, user_id, approved, created_at, type, scores_finished) VALUES(?,?,?,?,?,?,?,?,?)';
                $stmt= $pdoConnection->pdo->prepare($sql);
                return $stmt->execute([$startDate, $endDate, $prediction, $odds, $userId, $approved, gmdate("Y-m-d\ H:i:s"), $type, self::DEFAULT_SCROES_FINISHED]);
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
                    predictions.end_date, predictions.won, predictions.type, users.id AS user_id, users.name, users.sex, users.image_path
                    FROM predictions 
                    INNER JOIN users ON predictions.user_id = users.id 
                    WHERE predictions.approved = " . self::APPROVED . self::getQueryPredictionSQL($query, $isOddsQuery) . 
                    " ORDER BY predictions.start_date desc
                    LIMIT " . $limit . " OFFSET " . $offset . ";";
                    

                  // echo $sql; exit;
                return $pdoConnection->pdo->query($sql)->fetchAll();

            } catch(Exception $e) {
                var_dump($e->getMessage()); exit;
                return false;
            }
        }

        public static function getPredictionsByUserId($pdoConnection, $userId, $approved) {
            try {
                $sql = "SELECT (SELECT COUNT(*) FROM comments WHERE predictions.id=comments.id) AS total_comments,
                    predictions.prediction, predictions.id, predictions.created_at, predictions.start_date, 
                    predictions.end_date, predictions.won, predictions.type, users.id AS user_id, users.name, users.sex, users.image_path
                    FROM predictions 
                    INNER JOIN users ON predictions.user_id = users.id 
                    WHERE users.id=" . $userId . ($approved === null ? '' : " AND predictions.approved = " . $approved) . 
                    " ORDER BY predictions.start_date desc";
                // echo $sql; exit;
                    
                return $pdoConnection->pdo->query($sql)->fetchAll();

            } catch(Exception $e) {
                var_dump($e->getMessage()); exit;
                return false;
            }
        }

        public static function getPredictionsByIds($pdoConnection, $ids) {
            try {
                $sql = "SELECT id, prediction FROM predictions WHERE id IN (" . $ids . ")";
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                var_dump($e->getMessage()); exit;
                return false;
            }  
        }

        public static function getQueryPredictionSQL($query, $isOddsQuery) {
            if (!$query)
                return '';

            if ($isOddsQuery) {
                $minMAx = explode('_', $query);
                return $sql . ' AND predictions.total_odds >=' . $minMAx[0] . ' AND predictions.total_odds <=' . $minMAx[1]; 
            }

            if ((int)$query == HomeController::INPROGRESS_PREDICTION_QUERY) {
                return $sql . ' AND predictions.start_date < ' . "'" . gmdate("Y-m-d\ H:i:s") . "'" . " AND predictions.won IS NULL";
            }

            $won = (int)$query == HomeController::CORRECT_PREDICTION_QUERY ? self::PREDICTION_WON : self::PREDICTION_LOST;
            return $sql . ' AND predictions.won=' . $won;
        }
    }

?>