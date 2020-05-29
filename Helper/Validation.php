<?php
    class Validation {
        public function isValidName($name) {
            if (!preg_match("/^[a-zA-Z0-9 ]*$/",$name)) {
                return Array("isValid" => false, "message" => 'Name must contain only letters, numbers and white space');
            } else if (empty(trim($name))) {
                return Array("isValid" => false, "message" => 'Name is required');
            } else if (strlen($name) > 50) {
                return Array("isValid" => false, "message" => "Name can not be more than 50 characters");
            } else {
                return Array("isValid" => true, "message" => "");
            }
        }
    }

?>