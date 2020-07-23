<?php
    class DBConfig {
        const  dbConfig = array(
            'development' => array(
                'user' => 'root',
                'password' => 'root',
                'host' => '127.0.0.1',
                'database' => 'bet_community',
                'port' => 8889
            ),
    
            'production' => array(
                'user' => 'nextgbec_user1',
                'password' => 'Iloveodunayo123',
                'host' => 'localhost',
                'database' => 'nextgbec_bet_community',
                'port' => 3306
            )
        );
    }
?>
