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
                $stmt->execute([$startDate, $endDate, $prediction, $odds, $userId, $approved, gmdate("Y-m-d\ H:i:s"), $type, self::DEFAULT_SCROES_FINISHED]);
                return $pdoConnection->pdo->lastInsertId();
            } catch(Exception $e) {
                var_dump($e->getMessage()); exit;
                return false;
            }
        }

        public static function getPredictions($pdoConnection, $limit, $offset, $startDateInUTC, $endDateInUTC, $statusWon, $isPendingOutcomes, $myApprovedUserId) {
            try {
                $sql = "SELECT (SELECT COUNT(*) FROM predictions WHERE predictions.approved = 0) AS total, 
                     (SELECT COUNT(*) FROM likes WHERE predictions.id=likes.prediction_id) AS total_likes,
                    predictions.prediction, predictions.id, predictions.created_at, predictions.start_date, 
                    predictions.end_date, predictions.won, predictions.type, users.id AS user_id, users.name, users.sex, users.image_path
                    FROM predictions 
                    INNER JOIN users ON predictions.user_id = users.id 
                    WHERE predictions.approved = " . self::APPROVED . ($startDateInUTC && $endDateInUTC ? ' AND predictions.start_date >= ' . "'" . $startDateInUTC . "'" . ' AND predictions.start_date <= ' . "'" . $endDateInUTC . "'"
                    . ' AND predictions.end_date >= ' . "'" . $startDateInUTC . "'" . ' AND predictions.end_date <= ' . "'" . $endDateInUTC . "'" : '') . ($statusWon ? ' AND predictions.won =1' : '') . 
                    ($myApprovedUserId ? ' AND predictions.updated_by=' . $myApprovedUserId : '') .
                    ($isPendingOutcomes ? ' AND predictions.won IS NULL AND predictions.end_date <' . "'" . gmdate("Y-m-d\ H:i:s") . "'"  : '') .
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
                $sql = "SELECT (SELECT COUNT(*) FROM likes WHERE predictions.id=likes.prediction_id) AS total_likes,
                    predictions.prediction, predictions.id, predictions.created_at, predictions.start_date, 
                    predictions.end_date, predictions.won, predictions.type, users.send_email_notification, users.id AS user_id, users.name, users.sex, users.image_path
                    FROM predictions 
                    INNER JOIN users ON predictions.user_id = users.id 
                    WHERE users.id=" . $userId . ($approved === null ? '' : " AND predictions.approved = " . $approved) . 
                    " ORDER BY predictions.start_date desc";
                    
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

        public static function getPredictionById($pdoConnection, $id) {
            try {
                $sql = "SELECT user_id, start_date, won FROM predictions WHERE id=" . $id;
                return $pdoConnection->pdo->query($sql)->fetch();
            } catch(Exception $e) {
                var_dump($e->getMessage());
                return false;
            }  
        }

        public static function getPrediction($pdoConnection, $id) {
            try {
                $sql = "SELECT (SELECT COUNT(*) FROM predictions WHERE predictions.approved = 0) AS total, 
                (SELECT COUNT(*) FROM likes WHERE predictions.id=likes.prediction_id) AS total_likes,
               predictions.prediction, predictions.id, predictions.created_at, predictions.start_date, 
               predictions.end_date, predictions.won, predictions.type, users.id AS user_id, users.name, users.sex, users.image_path
               FROM predictions 
               INNER JOIN users ON predictions.user_id = users.id 
               WHERE predictions.id= " . $id;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                var_dump($e->getMessage());
                return false;
            } 
        }

        public static function deletePrediction($pdoConnection, $id) {
            try {
                $sql = "DELETE FROM predictions WHERE id=" . $id;
                $pdoConnection->pdo->query($sql);
                return true;
            } catch(Exception $e) {
                var_dump($e->getMessage());
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

        public static function createReportPrediction($pdoConnection, $predictionId, $problem, $note, $userId=null) {
            try {
                $sql = 'INSERT INTO bugs (prediction_id, user_id, problem, note) VALUES(?,?,?,?)';
                $stmt= $pdoConnection->pdo->prepare($sql);
                return $stmt->execute([$predictionId, $userId, $problem, $note]);
            } catch(Exception $e) {
                var_dump($e->getMessage()); exit;
                return false;
            }
        }

        public static function getUsersLastPredictions($pdoConnection, $userId, $numLastPredictions) {
            try {
                $sql = 'SELECT created_at FROM predictions WHERE user_id=' . $userId . ' ORDER BY created_at DESC LIMIT ' . $numLastPredictions;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                var_dump($e->getMessage());
                return false;
            }
        }

        public static function updatePredictionStatus($pdoConnection, $predictionId, $predictionStatus, $updatedBy) {
            try {
                $sql = 'UPDATE predictions SET won=' . $predictionStatus . ', updated_by=' . $updatedBy . ', date_updated=' . '"' . gmdate("Y-m-d\ H:i:s") . '"' . ' WHERE predictions.id=' . $predictionId;
                return $pdoConnection->pdo->query($sql);
            } catch(Exception $e) {
                var_dump($e->getMessage()); exit;
                return false;
            }
        }
    }
?>
