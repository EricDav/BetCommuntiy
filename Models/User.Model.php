<?php 

    class UserModel {
        const DEFAULT_COUNTRY = 'Nigeria';
        const DEFAULT_STATE = null;
        const DEFAULT_ROLE = 1;

        public static function getUser($pdoConnection, $email, $password) {
            try {
                $sql = 'SELECT * FROM users WHERE email=? AND password=?';
                $stmt= $pdoConnection->pdo->prepare($sql);
                $stmt->execute([$email, $password]);
                $stmt->fetch();
            } catch(Exception $e) {
                return 'Server error';
            }
        }

        public static function getUserByEmail($pdoConnection, $email) {
            try {
                $sql = 'SELECT * FROM users WHERE email=?';
                $stmt= $pdoConnection->pdo->prepare($sql);
                $stmt->execute([$email]);
                return $stmt->fetch();

            } catch(Exception $e) {
                return 'Server error';
            }  
        }

        public static function createUser($pdoConnection, $name, $email, $password, $sex,
            $country, $city) {
            
            if ($country == null) {
                $country = UserModel::DEFAULT_COUNTRY;
            }

            if ($state == null) {
                $state = UserModel::DEFAULT_STATE;
            }

            try {
                $sql = 'INSERT INTO users (name, email, password, sex, country, city, role, special_id) VALUES(?,?,?,?,?,?,?,?)';
                $stmt = $pdoConnection->pdo->prepare($sql);
                $specialId = uniqid();
                $stmt->execute([$name, $email, $password, $sex, $country, $city, UserModel::DEFAULT_ROLE, $specialId]);
                $temp = $stmt->fetch(PDO::FETCH_ASSOC);
                return ['specialId' => $specialId, 'id' => $pdoConnection->pdo->lastInsertId()];
            } catch(Exception $e) {
                var_dump($e);
                return null;
            }
        }

        public static function getFollowers($pdoConnection, $userId) {
            try {
                $sql = 'SELECT * FROM followers WHERE follower_id=' . $userId;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                var_dump($e);
                return null;
            }
        }
    }

?>