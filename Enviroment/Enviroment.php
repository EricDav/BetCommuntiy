<?php
    class Enviroment {
        public static function getEnv() {
            $host = explode(':', $_SERVER['HTTP_HOST']);
            return $host == 'localhost' ? 'development' : 'production';
        }
    }
?>
