<?php 
    class ContactModel {
            
        public function doesMessageExist($name, $email, $message, $isAUser, $userId, $pdoConnection) {
            try {
                $sql =  "SELECT * FROM `contacts`
                        WHERE `name` = ?	
                        AND `email` = ?
                        AND `message` = ?	
                        AND `registered` = ?
                        AND `user_id` = ?
                        ";
                        
                $stmt= $pdoConnection->pdo->prepare($sql);
                $stmt->execute([$name, $email, $message, $isAUser, $userId]);
                if($stmt->rowCount() > 0){
                    return true;
                }else{
                    return false;
                }
            } catch(Exception $e) {
                echo $e;
                return 'Server error';
            }
        }

        public function storeContactMessage($name, $email, $message, $isAUser, $userId, $request_time, $pdoConnection){
            try {
                $sql =  "INSERT INTO `contacts`(`name`, `email`, `message`, `registered`, `user_id`, `send_date`)
                        VALUES(?,?,?,?,?,?)";
                        
                $stmt= $pdoConnection->pdo->prepare($sql);
                if($stmt->execute([$name, $email, $message, $isAUser, $userId, $request_time])){
                    return true;
                }else{
                    return false;
                }
            } catch(Exception $e) {
                echo $e;
                return 'Server error';
            }
        }
    }
?>