<?php
class ErrorMail{

    public static function LogError($e){
        /**
         * Get log information
         */
        $file = basename($e->getFile());
        $code = $e->getCode();
        $line = $e->getLine();
        $errorMessage = $e->getMessage();
        $userIp = $_SERVER["REMOTE_ADDR"];
        $browser = $_SERVER["HTTP_USER_AGENT"];
        $browserDetails = get_browser(NULL, TRUE);

        /**
         * Send mail
         */

        $message = "<p>File: ".$file."</p><br/><br/>";
        $message .= "<p>Line: ".$line."</p><br/><br/>";
        $message .= "<p>Code: ".$code."</p><br/><br/>";
        $message .= "<p>Error Message: ".$errorMessage."</p><br/><br/>";
        $message .= "<p>Ip Address: ".$userIp."</p><br/><br/>";
        $message .= "<p>Browser Information: ".$browser."</p><br/><br/>";
        $message .= "<p>Browser Capabilities: ".$browserDetails."</p><br/><br/>";
        $mail = new SendMail(BetCommunity::DEFAULT_TODO_LOG_EMAIL, "System Error Information", $message);
        $mail->send();
    }

    public static function Log($file, $line, $errorMessage) {
        $userIp = $_SERVER["REMOTE_ADDR"];
        $browser = $_SERVER["HTTP_USER_AGENT"];
        $browserDetails = get_browser(NULL, TRUE);
        $userEmail = isset($_SESSION['userInfo']) ? $_SESSION['userInfo']['email'] : 'Not logged in';

        /**
         * Send mail
         */

        $message = "<p>File: ".$file."</p><br/>";
        $message .= "<p>Line: ".$line."</p><br/>";
        $message .= "<p>User: ".$userEmail . "</p><br/>";
        $message .= "<p>Error Message: ".$errorMessage."</p><br/>";
        $message .= "<p>Ip Address: ".$userIp."</p><br/>";
        $message .= "<p>Browser Information: ".$browser."</p><br/>";
        $message .= "<p>Browser Capabilities: ".$browserDetails."</p><br/>";

        $mail = new SendMail(BetCommunity::DEFAULT_TODO_LOG_EMAIL, "System Error Information", $message);
        $mail->send();
    }
}
?>
