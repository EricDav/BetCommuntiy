<?php 

    class UserModel {
        const DEFAULT_COUNTRY = 'Nigeria';
        const DEFAULT_STATE = null;
        const DEFAULT_ROLE = 1;
        const DEFAULT_IMAGE_PATH_MALE = '/bet_community/Public/images/default_male';
        const DEFAULT_IMAGE_PATH_FEMALE = '/bet_community/Public/images/default_female';

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
                $sql = 'INSERT INTO users (name, email, password, sex, country, city, role, special_id, image_path) VALUES(?,?,?,?,?,?,?,?,?)';
                $stmt = $pdoConnection->pdo->prepare($sql);
                $specialId = uniqid();
                $imagePath = $sex == 'M' ? self::DEFAULT_IMAGE_PATH_MALE : DEFAULT_IMAGE_PATH_FEMALE;
                $stmt->execute([$name, $email, $password, $sex, $country, $city, UserModel::DEFAULT_ROLE, $specialId, $imagePath]);
                $temp = $stmt->fetch(PDO::FETCH_ASSOC);
                return ['specialId' => $specialId, 'id' => $pdoConnection->pdo->lastInsertId()];
            } catch(Exception $e) {
                var_dump($e); exit;
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

        public static function getFeaturedUsers($pdoConnection) {
            try {
                $sql = 'SELECT users.id, users.name FROM featured_users INNER JOIN users ON
                 featured_users.user_id=users.id WHERE featured_users.featured_date > ' . "'" . gmdate("Y-m-d\ H:i:s", strtotime('-7 days')) .  "'";
                // echo $sql; exit;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                var_dump($e);
                return null;
            }
        }
    }

?>