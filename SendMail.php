<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require __DIR__ . '/PHPMailer/Exception.php';
    require __DIR__ . '/PHPMailer/PHPMailer.php';
    require __DIR__ . '/PHPMailer/SMTP.php';

    class SendMail {
        public function __construct($to, $subject, $message) {
            $this->to = $to;
            $this->subject = $subject;
            $this->message = $message;
            $this->mail = new PHPMailer(true);
        }

        public function send() {
            try {
                $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $this->mail->isSMTP();
                $this->mail->Host  = "smtp.gmail.com";
                $this->mail->SMTPAuth   = true;                                 // Enable SMTP authentication
                $this->mail->Username   = 'pythonboss123@gmail.com';            // SMTP username
                $this->mail->Password   = '';                       // SMTP password
                // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $this->mail->Port       = 25;      
                $this->mail->SMTPSecure = 'tsl';
                $this->mail->setFrom('info@betcommunity.com', 'BetCommunity');
                $this->mail->addAddress($this->to);
                $this->mail->isHTML(true);                                  // Set email format to HTML
                $this->mail->Subject = $this->subject;
                $this->mail->Body    = $this->message;
                $this->mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
?>
