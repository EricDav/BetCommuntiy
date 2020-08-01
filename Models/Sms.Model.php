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

        public static function updateBalance($pdoConnection, $phoneNumber, $balance) {
            try {
                $sql =  "UPDATE sms_bolt WHERE balance=" . "'" . balance. "'" . " WHERE phone_number=" . "'" . $phoneNumber . "'";
                return $pdoConnection->pdo->query($sql);
            } catch(Exception $e) {
                ErrorMail::LogError($e);
                return 'Server error';
            }
        }
    }

?>