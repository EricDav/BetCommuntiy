<?php
class ForgotPasswordModel{






    public static function getUserRequest($pdoConnection, $name, $email, $special_id, $request, $request_date_time){
        $sql =  "SELECT * FROM `temp_request`
                WHERE `name` = ?
                AND `email` = ?
                AND `special_id` = ?
                AND `request` = ?
                AND `expires` > ?";
        
        try{
            $stmt = $pdoConnection->pdo->prepare($sql);
            $stmt->execute([$name, $email, $special_id, $request, $request_date_time]);
            return $stmt->fetch();
        }catch(\PDOException $e){
            echo $e;
            return "Server error";
        }
    }






    public function doesUserRequestExist($pdoConnection, $identity){
        
        $sql =  "SELECT * FROM `temp_request`
                WHERE `identity` = ?";
        try{
            $stmt = $pdoConnection->pdo->prepare($sql);
            $stmt->execute([$identity]);
            return $stmt->fetch();
        }catch(\PDOException $e){
            echo $e;
            return 'Server error';
        }
    }






    public static function createRequest($pdoConnection, $name, $email, $special_id, $request, $request_date_time, $expires, $identity, $token, $token_to_send){
        $sql =  "INSERT INTO `temp_request`
                (`name`, `email`, `special_id`, `request`, `request_time`, `expires`, `identity`, `token`)
                VALUES(?,?,?,?,?,?,?,?)";
        try{
            $stmt = $pdoConnection->pdo->prepare($sql);

            if($stmt->execute([$name, $email, $special_id, $request, $request_date_time, $expires, $identity, $token])){
                return true;
            }else{
                return false;
            }
        }catch(\PDOException $e){
            echo $e;
            return "Server error";
        }
    }





    public static function updateRequest($pdoConnection, $name, $email, $special_id, $request, $request_date_time, $expires, $identity, $token, $token_to_send){
        $sql =  "UPDATE `temp_request`
                SET `identity` = ?, `token` = ?, `request_time` = ?, `expires` = ?
                WHERE `name` = ?
                AND `email` = ?
                AND `special_id` =?
                AND `request` =?";

        try{
            $stmt = $pdoConnection->pdo->prepare($sql);

            if($stmt->execute([$identity, $token, $request_date_time, $expires, $name, $email, $special_id, $request])){
                return true;
            }else{
                return false;
            }
        }catch(\PDOException $e){
            echo $e;
            return "Server error";
        }
    }





    public function getSpecialIdFromTempRequest($pdoConnection, $identity){
        try{
            $query =    "SELECT `special_id`
                        FROM `temp_request`
                        WHERE `identity` = ?";
            $stmt = $pdoConnection->pdo->prepare($query);
            $stmt->execute([$identity]);
            return $stmt->fetch();
        }catch(\PDOException $e){
            echo $e;
            return 'Server error';
        }
    }






    public function UpdatePassword($pdoConnection, $specialId, $password){
        try{
            $query =    "UPDATE `users`
                        SET `password` = ?
                        WHERE `special_id` =?";
            $stmt = $pdoConnection->pdo->prepare($query);
            if($stmt->execute([$password,$specialId])) return true;
            else return false;
        }catch(\PDOException $e){
            echo $e;
            return 'Server error';
        }
    }
    




    public function deleteUserRequest($pdoConnection, $identity){
        try{
            $query =    "DELETE FROM `temp_request`
                        WHERE `identity` =?";
            $stmt = $pdoConnection->pdo->prepare($query);
            if($stmt->execute([$identity])) return true;
            else return false;
        }catch(\PDOException $e){
            echo $e;
            return 'Server error';
        }
    }





    public function getUserPassword($pdoConnection, $specialId){
        try{
            $query =    "SELECT `password` FROM `users`
                        WHERE `special_id` =?";
            $stmt = $pdoConnection->pdo->prepare($query);
            $stmt->execute([$specialId]);
            return $stmt->fetch();
        }catch(\PDOException $e){
            echo $e;
            return 'Server error';
        }
    }





    public static function getAllTokenRequest($pdoConnection){
        try{
            $query = "SELECT * FROM `temp_request`";
            $stmt = $pdoConnection->pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            echo $e;
            return false;
        }
    }





    public static function deleteExpiredTokenRequest($pdoConnection, $id){
        try{
            $query =    "DELETE FROM `temp_request`
                        WHERE `id` = ".$id;
            $stmt = $pdoConnection->pdo->query($query);
        }catch(\PDOException $e){
            echo $e;
            return false;
        }
    }

}
?>
