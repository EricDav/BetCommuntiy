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
        $mail = new SendMail();
        $message = "<p>File: ".$file."</p><br/><br/>";
        $message .= "<p>Line: ".$line."</p><br/><br/>";
        $message .= "<p>Code: ".$code."</p><br/><br/>";
        $message .= "<p>Error Message: ".$errorMessage."</p><br/><br/>";
        $message .= "<p>Ip Address: ".$userIp."</p><br/><br/>";
        $message .= "<p>Browser Information: ".$browser."</p><br/><br/>";
        $message .= "<p>Browser Capabilities: ".$browserDetails."</p><br/><br/>";
        $mail->setMessage($message);
        $mail->subject = "System Error Information";
        $mail->to = "info@betcommunity.com";
        $mail->send();
    }
}
?>