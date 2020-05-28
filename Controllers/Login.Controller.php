<?php

class LoginController extends Controller {

    public function  __construct($request) {
        parent::__construct($request);
    }

    public function validate() {
        return true;
    }

    public function perform() {
        
    }
}

?>