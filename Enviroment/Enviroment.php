<?php
    class Enviroment {
        public static function getEnv() {
            $envObj = json_decode(file_get_contents(__DIR__ .'/../.envJson'));
            return $envObj->enviroment;
        }
    }
?>
