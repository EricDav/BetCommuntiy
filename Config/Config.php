<?php
    class DBConfig {
        const  dbConfig = array(
            'development' => array(
                'user' => 'root',
                'password' => 'root',
                'host' => 'localhost',
                'database' => 'bet_community',
                'port' => 8889
            ),
    
            'production' => array(
                'user' => 'root',
                'password' => 'root',
                'host' => '127.0.0.1',
                'database' => 'bet_community',
                'port' => 8889
            )
        );
    }
?>
