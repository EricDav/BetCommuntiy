<?php

include 'BetCommunity.class.php';
spl_autoload_register(function ($name) {
    $classPaths =  BetCommunity::loads;
    if (file_exists($classPaths[$name])) {
        include $classPaths[$name];
    } else {
        echo "file not found";
    }
});

$enviroment = Enviroment::getEnv();
$baseRoute = $enviroment == 'development' ? file_get_contents('base.route') : '';
var_dump($baseRoute);
$request = new Request($baseRoute); // Create a request object

var_dump($request);
//  $data = "Hello World 1";
//  var_dump(BetCommunity::routes);
//  $t = 'LoginController';
//  $s = new $t('r');
//  var_dump($s->validate());

// include 'Pages/s.php'
// $r = file_get_contents('Pages/s.php');

?>
