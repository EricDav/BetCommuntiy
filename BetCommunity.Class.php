<?php

class BetCommunity {
    const routes = [
        '/login' => "LoginController"
    ];

    // saves class name as key and path as value
    const loads = [
        'Enviroment' => 'Enviroment/Enviroment.php',
        'Request' => 'Request.php',
        'Controller' => 'Controllers/Controller.php',
        'LoginController' => 'Controllers/Login.Controller.php'
    ];
}

?>
