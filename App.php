<?php
session_start();
include 'BetCommunity.Class.php';

function isLogin() {
    if (isset($_SESSION['userInfo'])) {
        return true;
    }
    return false;
}

spl_autoload_register(function ($name) {
    $classPaths =  BetCommunity::loads;
    if (file_exists($classPaths[$name])) {
        include $classPaths[$name];
    } else {
        die($name . " class not found");
    }
});

$enviroment = Enviroment::getEnv();
$request = new Request(); // Create a request object
$data = null;

if (in_array($request->route, array_keys(BetCommunity::routes))) {

    $controllerArr = explode('@', BetCommunity::routes[$request->route]);
    $controllerClass = $controllerArr[0];
    $method = sizeof($controllerArr) == 2 ? $controllerArr[1] : BetCommunity::DEFAULT_METHOD;
    $controllerObject = new $controllerClass($request);

    if ($method != BetCommunity::DEFAULT_METHOD) {
        $controllerObject->$method();
    } else {
        if ($controllerObject->validate()) {
            $controllerObject->perform();
         }
    }

    $controllerObject->setToken();
    $data = $controllerObject->data;
    $data['isLogin'] = $controllerObject->isLogin();

    // var_dump($data['template']); exit;
    include 'Pages/' . $data['template'];

} else{
    include './Pages/404.php'; //include error 404 page for undefined routes
 }

?>

