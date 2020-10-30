<?php
    class ScoresModel {
        public static function createScore($pdoConnection, $scores) {
            try {
                $pdoConnection->pdo->beginTransaction();
                foreach($scores as $score) {
                    $pdoConnection->pdo->exec('INSERT INTO match_results (match_id, home_name, away_name, score, created_at) VALUES(' . "'" . $score->match_id . "'" . ',' . "'" . $score->home_name . "'"  . ',' . "'" . $score->away_name . "'" . ',' . "'" . $score->score . "'" . ',' . "'" . gmdate("Y-m-d\ H:i:s") . "'" . ')');
                }
    
                $pdoConnection->pdo->commit();
                return true;
            } catch(Exception $e) {
                var_dump($e); exit;
                $pdoConnection->pdo->rollBack();
                return false;
            }
        }

    }

?>