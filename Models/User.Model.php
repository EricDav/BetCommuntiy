<?php 

    class UserModel {
        const DEFAULT_COUNTRY = 'Nigeria';
        const DEFAULT_STATE = null;
        const DEFAULT_ROLE = 1;
        const DEFAULT_IMAGE_PATH_MALE = 'default_male.png';
        const DEFAULT_IMAGE_PATH_FEMALE = 'default_female.png';

        public static function getUser($pdoConnection, $email, $password) {
            try {
                $sql = 'SELECT * FROM users WHERE email=? AND password=?';
                $stmt= $pdoConnection->pdo->prepare($sql);
                $stmt->execute([$email, $password]);
                return $stmt->fetchAll();
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return 'Server error';
            }
        }

        public static function getUserByEmailPhone($pdoConnection, $email, $phoneNumber) {
            try {
                $sql = 'SELECT * FROM users WHERE email=? AND phone_number=?';
                $stmt= $pdoConnection->pdo->prepare($sql);
                $stmt->execute([$email, $phoneNumber]);
                return $stmt->fetchAll();
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return 'Server error';
            }
        }

        public static function getUserByPhoneNumber($pdoConnection, $phoneNumber) {
            try {
                $sql = 'SELECT * FROM users WHERE phone_number=?';
                $stmt= $pdoConnection->pdo->prepare($sql);
                $stmt->execute([$phoneNumber]);
                return $stmt->fetchAll();
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return 'Server error';
            }
        }

        public static function getSubscribers($pdoConnection, $userId) {
            try {
                $sql = 'SELECT * FROM subscribers WHERE subscriber_id=?';
                $stmt= $pdoConnection->pdo->prepare($sql);
                $stmt->execute([$userId]);
                return $stmt->fetchAll();
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return 'Server error';
            }
        }

        public static function getUserById($pdoConnection, $id) {
            try {
                $sql = 'SELECT *, (SELECT COUNT(*) FROM followers WHERE user_id =' . $id . ') AS num_followers  FROM users WHERE id=?';
                $stmt= $pdoConnection->pdo->prepare($sql);
                $stmt->execute([$id]);
                return $stmt->fetchAll();
            }
            catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function getUserByEmail($pdoConnection, $email) {
            try {
                $sql = 'SELECT * FROM users WHERE email=?';
                $stmt= $pdoConnection->pdo->prepare($sql);
                $stmt->execute([$email]);
                return $stmt->fetch();

            } catch(Exception $e) {
                ErrorMail::LogError($e);
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
                $sql = 'INSERT INTO users (name, email, password, sex, country, city, role, special_id, image_path, date_created) VALUES(?,?,?,?,?,?,?,?,?,?)';
                $stmt = $pdoConnection->pdo->prepare($sql);
                $specialId = uniqid();
                $imagePath = $sex == 'M' ? self::DEFAULT_IMAGE_PATH_MALE : DEFAULT_IMAGE_PATH_FEMALE;
                $stmt->execute([$name, $email, $password, $sex, $country, $city, UserModel::DEFAULT_ROLE, $specialId, $imagePath, gmdate("Y-m-d\ H:i:s")]);
                return ['specialId' => $specialId, 'id' => $pdoConnection->pdo->lastInsertId()];
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function updateUserDetails($pdoConnection, $name, $email, $sex,
            $country, $city, $phoneNumber, $userId) {
            
            if ($country == null) {
                $country = UserModel::DEFAULT_COUNTRY;
            }

            try {
                $sql = 'UPDATE users SET name=?, email=?, sex=?, country=?, city=?, phone_number=? WHERE id=' . $userId;
                $stmt = $pdoConnection->pdo->prepare($sql);

                $stmt->execute([$name, $email, $sex, $country, $city, $phoneNumber]);
                return true;
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function updateUserPassword($pdoConnection, $password, $userId, $code=null) {
            try {
                $sql = 'UPDATE users SET password=?' . ($code ? ', code_token=NULL, token_count=0' : '') . ' WHERE id=' . $userId;
                $stmt = $pdoConnection->pdo->prepare($sql);

                $stmt->execute([$password]);
                return true;
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function updateUserProfilePhotoUrl($pdoConnection, $url, $userId) {
            try {
                $sql = 'UPDATE users SET image_path=? WHERE id=' . $userId;
                $stmt = $pdoConnection->pdo->prepare($sql);

                $stmt->execute([$url]);
                return true;
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function getFollowers($pdoConnection, $userId) {
            try {
                $sql = 'SELECT * FROM followers WHERE follower_id=' . $userId;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return null;
            }
        }

        public static function getUserFollowersCount($pdoConnection, $userId) {
            try {
                $sql = 'SELECT COUNT(*) AS total_followers  FROM followers WHERE user_id=' . $userId;
                return $pdoConnection->pdo->query($sql)->fetch();
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function getUsersFollowers($pdoConnection, $userId) {
            try {
                $sql = 'SELECT followers.user_id, followers.follower_id, users.name, users.image_path,
                (SELECT COUNT(*) FROM predictions WHERE predictions.user_id= users.id) AS total_predictions,
                (SELECT COUNT(*) FROM predictions WHERE predictions.user_id= users.id AND predictions.won = 1) AS total_predictions_won
                FROM followers INNER JOIN users ON followers.follower_id = users.id
                WHERE followers.user_id=' . $userId;
                
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function getFeaturedUsers($pdoConnection) {
            try {
                $sql = 'SELECT users.id, users.name, users.image_path FROM featured_users INNER JOIN users ON
                 featured_users.user_id=users.id WHERE featured_users.featured_date > ' . "'" . gmdate("Y-m-d\ H:i:s", strtotime('-7 days')) .  "'";
                // echo $sql; exit;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                // echo 'Home'; exit;
                ErrorMail::LogError($e);
                return null;
            }
        }

        public static function addFollower($pdoConnection, $followerId, $userId) {
            try {
                $sql = 'INSERT INTO followers (follower_id, user_id) VALUES(' . $followerId . ',' . $userId . ')';
                $pdoConnection->pdo->query($sql);
                return true;
            } catch(Exception $e) {
                ErrorMail::LogError($e); 
                return false;
            }
        }

        public static function getFollower($pdoConnection, $followerId, $userId) {
            try {
                $sql = 'SELECT * FROM followers WHERE user_id=' . $userId . ' AND follower_id=' . $followerId;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function removeFollower($pdoConnection, $followerId, $userId) {
            try {
                $sql = 'DELETE FROM followers WHERE user_id=' . $userId . ' AND follower_id=' . $followerId;
                $pdoConnection->pdo->query($sql);
                return true;
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function like($pdoConnection, $predictionId, $userId) {
            try {
                $sql = 'INSERT INTO likes (prediction_id, user_id) VALUES(' . $predictionId . ',' . $userId . ')';
                // echo $sql; exit;
                $pdoConnection->pdo->query($sql);
                return true;
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function unlike($pdoConnection, $predictionId, $userId) {
            try {
                $sql = 'DELETE FROM likes WHERE prediction_id=' . $predictionId . ' AND user_id=' . $userId;
                // echo $sql; exit;
                return $pdoConnection->pdo->query($sql);
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function getLike($pdoConnection, $predictionId, $userId) {
            try {
                $sql = 'SELECT * FROM likes WHERE prediction_id=' . $predictionId . ' AND user_id=' . $userId;
                return $pdoConnection->pdo->query($sql)->fetch();
            } catch(Exception $e) {
                var_dump($e->getMessage());
                return 'Server error';
            }
        }

        public static function getAllUsers($pdoConnection) {
            try {
                $sql = 'SELECT users.name, users.image_path, users.id, (SELECT COUNT(*) FROM predictions WHERE predictions.user_id= users.id) AS total_predictions,
                (SELECT COUNT(*) FROM predictions WHERE predictions.user_id= users.id AND predictions.won = 1) AS total_predictions_won FROM users';
                return $pdoConnection->pdo->query($sql)->fetchAll();

            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function updateUserResetCode($pdoConnection, $email, $code, $count) {
            try {
                $sql = 'UPDATE users SET code_token=' . "'" . $code . "'" . ', token_count=' . $count . ' WHERE email=' . "'" . $email . "'";
                // echo $sql; exit;
                return $pdoConnection->pdo->query($sql);

            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }   
        }
    }
?>
