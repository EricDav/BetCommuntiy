<?php

include 'BetCommunity.class.php';
spl_autoload_register(function ($name) {
    $classPaths =  BetCommunity::loads;
    if (file_exists($classPaths[$name])) {
        include $classPaths[$name];
    } else {
        echo $name . "class not found";
    }
});

$enviroment = Enviroment::getEnv();
$request = new Request(); // Create a request object
$data = null;


if (in_array($request->route, array_keys(BetCommunity::routes))) {
     $controllerClass = BetCommunity::routes[$request->route];
     
     $controllerObject = new $controllerClass($request);

    if ($controllerObject->validate()) {
        $controllerObject->perform();
    }

    $data = $controllerObject->data;
    if ($controllerObject->responseType == 'html'){
         include 'Pages/' . $data['template'];
    }  
}else{
    include './Pages/404.php'; //include error 404 page for undefined routes
}
?>

