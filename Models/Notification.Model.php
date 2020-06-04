<?php
class NotificationModel {

    public static function getNotification($conn, $request){
        $controller = new NotificationController();
        $user_id = $request['userId'];
        $query =   "SELECT * FROM `notifications` INNER JOIN `users` 
                    ON notifications.user_id = users.id 
                    WHERE notifications.user_id = :user_id
                    ORDER BY notifications.created_at DESC";
        try{
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        }catch(\PDOException $e){
            $controller->jsonResponse(array('success' => true, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'messages' => 'Sever Error'));
            exit;
        } 

        if($stmt->rowCount() == 0){
            $error = 'No new notification';
            $controller->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'messages' => $error));
        }else{
            //check for unread
            $unread;
            foreach($stmt as $value){
                if($value['isRead'] == false ){
                    $unread++;
                }
            }
        } 
        $data = [
            'unread' => $unread,
            'totalNotification' => $stmt->rowCount,
            'result' => $stmt->fetchAll(PDO::FETCH_ASSOC),
        ];

        $conn = null;//close connection;
        $controller->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'data' => $data, 'messages' => 'success'));


    }

    /**
     * Register notification from other user actions
     */
    public static function regiserOtherNotification($conn, $request){
        $controller = new NotificationController();
        $notification = $request['message'];
        $userId = $request['userId'];
        $isRead = false;
        $recipient = $request['recipient'];

        /**
         * create user notification from user own actions
         */
        if($receipient == 'self')
        {
            $query = "
                INSERT INTO `notifications`(`notification`, `user_id`, `isRead`);
                VALUES(?, ?, ?)
            ";
            try{
                $stmt = $conn->preprare($query);
                if($stmt->execute([$notification, $userId, $isRead])){
                    $controller->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'data' => $data, 'messages' => 'Notification successfully entered'));
                }
            }catch(\PDOException $e){
                return false;
            }
        }
        /**
         * create notification from user own action and register for followers
         */
        else
        {
            $query = "
                SELECT `follower_id`
                FROM `followers`
                WHERE `user_id` = :user_id
            ";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if($stmt->rowCount() == 0){
                return false;
            }else{
                foreach($stmt as $result){
                    $query = "
                        INSERT INTO `notifications`(`notification`, `user_id`, `isRead`);
                        VALUES(?, ?, ?)
                    ";
                    try{
                        $stmt = $conn->preprare($query);
                        if($stmt->execute([$notification, $result['follower_id'], $isRead])){
                            $controller->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'data' => $data, 'messages' => 'Notification successfully entered'));
                        }
                    }catch(\PDOException $e){
                        return false;
                    }
                }
            }
        }
    }
}
?>