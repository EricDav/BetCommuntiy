<?php 
    class NotificationModel {
        public static function creatNotifictionsForPrediction($pdoConnection, $userId, $predictionId) {
            try {
                $notification = 'just created a prediction';
                $link = '/predictions?id=' . $predictionId;
                $followers = FollowerModel::getFollowers($pdoConnection, $userId);
                $emails = array();
                if (!$followers) {
                    return false;
                }

                $pdoConnection->pdo->beginTransaction();
                foreach($followers as $follower) {
                    array_push($emails, $follower['email']);
                    $followerId = $follower['follower_id'];

                    $pdoConnection->pdo->exec('INSERT INTO notifications (user_id, ref_id, notification, link, created_at) 
                        VALUES(' . $followerId . ',' . $userId . ',' . "'" . $notification ."'" . ',' . "'" . $link ."'" . ',' . "'" . gmdate("Y-m-d\ H:i:s") . "'" . ')');
                }

                $pdoConnection->pdo->commit();
                self::sendEmailNotification($emails, $_SESSION['userInfo']['name'], $followers[0]['name'], $id);
                return true;

            } catch(Exception $e) {
                $pdoConnection->pdo->rollBack();
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function sendEmailNotification($emails, $name, $receiver, $id) {
            if (sizeof($emails) > 0) {
                $sendEmail = new SendMail($emails, '4CastBet | Prediction Notification', self::getMessage($name, $receiver, $id));
            }
        }

        public function getMessage($name, $receiver, $id) {
            $htmlMessage = '<div style="margin-left: 10px; margin-top: 20px; line-height: 1.5; text-align: left"><b>Hi ' . $name . ',</b>, ' .  '<br><span font-weight: 500;>' . $receiver . ' just drop a prediction</span>' . '</div>';
            $htmlMessage .='<div style="margin-left: 10px;line-height: 1.5; text-align: left">Click the <a href="http://4castbet.com/predictions?id="' + $id + '>link to view the prediction</div>';
    
            return $htmlMessage;
        }

        public static function getPredictions($pdoConnection, $userId) {
            try {
                $sql = 'SELECT notifications.notification, notifications.created_at, notifications.link, notifications.is_seen, 
                        notifications.is_read, users.name, users.image_path FROM notifications INNER JOIN users ON notifications.ref_id=users.id WHERE user_id=' . $userId;
                return $pdoConnection->pdo->query($sql)->fetchAll();
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function clearSeen($pdoConnection, $userId) {
            try {
                $sql = 'UPDATE notifications SET is_seen=1 WHERE user_id=' . $userId;
                return $pdoConnection->pdo->query($sql);
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function updateSettingsById($pdoConnection, $userId, $settingsType, $settings) {
            try {
                $sql = 'UPDATE users SET ' . $settingsType . '=' . $settings .  ' WHERE id=' . $userId;
                return $pdoConnection->pdo->query($sql);
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }

        public static function updateSettingsByEmail($pdoConnection, $email, $settingsType, $settings, $attr) {
            try {
                $sql = 'UPDATE users SET ' . $settingsType . '=' . $settings .  ' WHERE email=' . $email;
                return $pdoConnection->pdo->query($sql);
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return false;
            }
        }
    }
?>
