<?php 

    class UserModel {
        const DEFAULT_COUNTRY = 'Nigeria';
        const DEFAULT_STATE = null;

        public static function getUser($pdoConnection, $email, $password) {
            try {
                $sql = 'SELECT * FROM users WHERE email=? AND password=?';
                $stmt= $pdoConnection->prepare($sql);
                return $stmt->execute([$email, $password]);
            } catch(Exception $e) {
                return false;
            }
        }

        public static function createUser($pdoConnection, $name, $email, $password, $sex,
            $country, $state) {
            
            if ($country == null) {
                $country = UserModel::DEFAULT_COUNTRY;
            }

            if ($state == null) {
                $state = UserModel::DEFAULT_STATE;
            }

            try {
                $sql = 'INSERT INTO users (name, email, password, sex, country, state) VALUES(?,?,?,?,?,?)';
                $stmt= $con->prepare($sql);
                return $stmt->execute([$name, $email, $password, $sex, $country, $state]);
            } catch(Exception $e) {
                return false;
            }
        }
    }

?>