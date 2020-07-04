<?php
    class Enviroment {
        public static function getEnv() {
            $host = explode(':', $_SERVER['HTTP_HOST']);
            // echo $host; exit;
            return $host[0] == 'localhost' ? 'development' : 'production';
        }
    }
?>
