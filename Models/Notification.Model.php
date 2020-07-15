<?php 
    class NotificationModel {
        public static function creatNotifictionsForPrediction($pdoConnection, $userId, $predictionId) {
            try {
                $notification = 'just created a prediction';
                $link = '/predictions?id=' . $predictionId;
                $followers = FollowerModel::getFollowers($pdoConnection, $userId);

                if (!$followers) {
                    return false;
                }

                $pdoConnection->pdo->beginTransaction();
                foreach($followers as $follower) {
                    $followerId = $follower['follower_id'];

                    $pdoConnection->pdo->exec('INSERT INTO notifications (user_id, ref_id, notification, link, created_at) 
                        VALUES(' . $followerId . ',' . $userId . ',' . "'" . $notification ."'" . ',' . "'" . $link ."'" . ',' . "'" . gmdate("Y-m-d\ H:i:s") . "'" . ')');
                }

                $pdoConnection->pdo->commit();
                return true;

            } catch(Exception $e) {
                $pdoConnection->pdo->rollBack();
                echo $e->getMessage();
                return false;
            }
        }

        public static function getPredictions($pdoConnection, $userId) {
            try {
                $sql = 'SELECT notifications.notification, notifications.created_at, notifications.link, notifications.is_seen, 
                        notifications.is_read, users.name, users.image_path FROM notifications INNER JOIN users ON notifications.ref_id=users.id WHERE user_id=' . $userId;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public static function clearSeen($pdoConnection, $userId) {
            try {
                $sql = 'UPDATE notifications SET is_seen=1 WHERE user_id=' . $userId;
                return $pdoConnection->pdo->query($sql);
            } catch(Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public static function updateSettingsById($pdoConnection, $userId, $settingsType, $settings) {
            try {
                $sql = 'UPDATE users SET ' . $settingsType . '=' . $settings .  ' WHERE id=' . $userId;
                return $pdoConnection->pdo->query($sql);
            } catch(Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public static function updateSettingsByEmail($pdoConnection, $email, $settingsType, $settings, $attr) {
            try {
                $sql = 'UPDATE users SET ' . $settingsType . '=' . $settings .  ' WHERE email=' . $email;
                return $pdoConnection->pdo->query($sql);
            } catch(Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }
?>
