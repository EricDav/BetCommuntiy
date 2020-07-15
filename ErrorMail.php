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
       

        /**
         * Send mail
         */
     
        $message = "<p>File: ".$file."</p>";
        $message .= "<p>Line: ".$line."</p>";
        $message .= "<p>Code: ".$code."</p>";
        $message .= "<p>Error Message: ".$errorMessage."</p>";
        $message .= "<p>Ip Address: ".$userIp."</p>";
        $message .= "<p>Browser Information: ".$browser."</p>";
        $subject = "System Error Information";
        $to = "info@betcommunity.com";
        $mail = new SendMail($to, $subject, $message);
        $mail->send();
    }
}
?>