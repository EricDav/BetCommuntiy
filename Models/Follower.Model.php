<?php 
    class FollowerModel {
        public static function getFollowers($pdoConnection, $userId) {
            try {
                $sql = 'SELECT follower_id  FROM followers WHERE user_id=' . $userId;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                var_dump($e->getMessage());
                return false;
            }
        }
    }


?>