<?php
    class AboutController extends Controller {
        public function __construct($request) {
            parent::__construct($request);
        }

        public function validate() {
            return true;
        }

        public function perform() {
            $this->data['template'] = 'About.php';
            $this->data['title'] = 'BetCommunity | About Us';
            $this->responseType = 'html';
        }
    }

?>