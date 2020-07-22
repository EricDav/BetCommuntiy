<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require __DIR__ . '/PHPMailer/Exception.php';
    require __DIR__ . '/PHPMailer/PHPMailer.php';
    require __DIR__ . '/PHPMailer/SMTP.php';

    class SendMail {
        
        public function __construct($to, $subject, $message, $isHeaderFooterSet=false) {
            $this->envObj = json_decode(file_get_contents(__DIR__ .'/.envJson'));
            $this->isHeaderFooterSet = $isHeaderFooterSet;
            $this->to = $to;
            $this->subject = $subject;
            $this->message = '';
            $this->mail = new PHPMailer(true);
            $this->setMessage($message);
        }

        public function getEmailHeader() {
            $emailHeader = '<html><body style="font-size: 0.9rem;"><center>
                    <div style="width: 100%; background-color: #f5f6f7; padding-top: 50px; padding-bottom: 50px;">';
            $emailHeader .= '<div style="width: 80%; border-width: 1px;  border-style: solid; height: fit-content; background-color: #fff;">';

            $emailHeader .= '<div style="width: 100%;border-top: 0px;border-left: 0px;border-bottom: 1px;border-right: 0px;border-style: solid;height: 50px; background-color: rgb(35, 31, 32)">

                                <center><img width =60%; height=40;" src="http://4castbet.com/bet_community/Public/images/logo1.png" alt="logo"></center>
                            </div>';

            return $emailHeader;
        }

        public function getEmailFooter() {
            $emailFooter = "";
            $emailFooter .='<div style="text-align: center; margin-top: 50px; height: 50px; margin-bottom: -5;">
                                <center>
                                    <div style="color: #27aae1;font-weight: 700;">Contact Us</div>
                                    <span style="color: black; font-weight: 700;">+1 (234) 222 0754</span> 
                                    <span style="color: black;font-weight: 700;margin-left: 10px;">info@4castbet.com</span>
                                    <div style="color: black; font-weight: 600; font-style: oblique;">Visit our <a href="http://www.4castbet.com">website</a> to view our latest predictions</div>
                                </center>
                            </div>';


            $emailFooter .='<div style="background: #231F20;text-align: center;color: #fff;top: 457;width: 100%; height: 45px; border-bottom: 0px;">
                                <center>
                                    <p style="padding-top: 10px;">4CastBet.com © 2020. All rights reserved</p>
                                </center>
                            </div>';
            $emailFooter .='</div>';
            $emailFooter .='</div></center><html><body>';
            
            return $emailFooter;
        }

        public function setMessage($message) {
            if (!$this->isHeaderFooterSet) {
                $this->message = $this->getEmailHeader() . $message .  $this->getEmailFooter();
            } else {
                $this->message = $message;
            }
        }

        public function send() {
            try {
                $this->mail->isHTML(true); 
                $this->mail->isSMTP();
                $this->mail->Host  = "mail.4castbet.com";
                $this->mail->SMTPAuth   = true;                                 // Enable SMTP authentication
                $this->mail->Username   = $this->envObj->email;            // SMTP username
                $this->mail->Password   = $this->envObj->password;                      // SMTP password
                // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $this->mail->Port       = 25;      
                $this->mail->SMTPSecure = 'tsl';
                $this->mail->setFrom('info@4castbet.com');
                if (is_array($this->to)) {
                    foreach($this->to as $email) {
                        $this->mail->addAddress($email); 
                    }
                } else {
                    $this->mail->addAddress($this->to); 
                }                    
                $this->mail->Subject = $this->subject;
                $this->mail->Body    = $this->message;
                $this->mail->send();
                return true;
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
                return false;
            }
        }
    }
?>
