<?php 
    class FollowerModel {
        public static function getFollowers($pdoConnection, $userId) {
            try {
                $sql = 'SELECT follower_id, users.email, users.name  FROM followers INNER JOIN users ON users.id=followers.follower_id WHERE user_id=' . $userId;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }
    }
?>
