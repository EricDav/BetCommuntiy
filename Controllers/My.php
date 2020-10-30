<?php
    class MyController extends Controller {
        public function __construct($request) {
            parent::__construct($request);
        }

        public function my() {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];
            $s = new SendMail('alienyidavid4christ@gmail.com', 'Contact From My Website', $message . "\n" . ' From' . $email);

            if ($s) {
                $this->jsonResponse(array('success' => true, 'code' => 200, 'messages' => 'Message sent successfully'));
            }
            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'messages' => 'Server error'));
        }
        public function validate() {
        }

        public function perform() {
        }
    }

?>