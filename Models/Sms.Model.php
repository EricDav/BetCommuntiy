<?php 
    class SmsModel {
        public static function getBalance($pdoConnection, $phoneNumber) {
            try {
                $sql =  "SELECT * FROM sms_bolt WHERE phone_number=" . "'" . $phoneNumber . "'";
                $sms = $pdoConnection->pdo->query($sql)->fetch();

                if (!$sms) {
                    return $sms;
                }
                return (object)$sms;
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return 'Server error';
            }
        }

        public static function createSmsUser($pdoConnection, $phoneNumber) {
            try {
                $sql =  "INSERT INTO sms_bolt (phone_number, credits) VALUES(?,?)";
                $stmt= $pdoConnection->pdo->prepare($sql);
                return $stmt->execute([$phoneNumber, '100']);

            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return 'Server error';
            }
        }

        public static function updateBalance($pdoConnection, $phoneNumber, $balance) {
            try {
                $sql =  "UPDATE sms_bolt SET credits=" . "'" . $balance. "'" . " WHERE phone_number=" . "'" . $phoneNumber . "'";
                return $pdoConnection->pdo->query($sql);
            } catch(Exception $e) {
                // var_dump($e->getMessage());
                ErrorMail::LogError($e);
                return false;
            }
        }
    }
?>
