<?php
    class Enviroment {
        public static function getEnv() {
            return $_SERVER['HTTP_HOST'] == 'localhost' ? 'development' : 'production';
        }
    }
?>
