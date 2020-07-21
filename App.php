<?php
session_start();
include 'BetCommunity.Class.php';
$envObj = json_decode(file_get_contents(__DIR__ .'/.envJson'));


/**
* Given a competition object it returns 
* the name e.g England Premier league
*/
  function getCompetitionName($competition) {
    if (sizeof($competition->countries) > 0) {
      return $competition->countries[0]->fifa_code ? $competition->countries[0]->fifa_code . ' - ' . $competition->name :  $competition->countries[0]->name . ' - ' . $competition->name;
    }

    if (sizeof($competition->federations) > 0) {
      return $competition->federations[0]->name . ' - ' . $competition->name;
    }

    return $competition->name;
  }

function isLogin() {
    if (isset($_SESSION['userInfo'])) {
        return true;
    }
    return false;
}

function isAdmin() {
    if (isLogin() && (int)$_SESSION['userInfo']['role'] > 1)
        return true;
    
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

/**
 * Re-logs a user 
 */
if (!isset($_SESSION['userInfo']) && $request->route != '/logout') {
    // check for token
    if (isset($_COOKIE['__uii']) &&  isset($_COOKIE['__uiispecial'])) {
        $userId = is_numeric($_COOKIE['__uii']) ? (int)$_COOKIE['__uii'] - BetCommunity::DEFAULT_ADD_PROFILE : null;

        if ($userId) {
            $pdoConnection = new PDOConnection();
            $pdoConnection->open();
            $user = UserModel::getUserById($pdoConnection, $userId);

            if ($user) {
                $user = $user[0];

                if ($user && $_COOKIE['__uiispecial'] == $user['special_id']) {
                    $userInfo = array(
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'specialId' => $user['special_id'],
                        'role' => $user['role'],
                        'imagePath' => $user['image_path'],
                        'phoneNumber' => $user['phone_number']
                    );

                    $_SESSION['userInfo'] = $userInfo;
                }
            }
        }
    }
}

if (in_array($request->route, array_keys(BetCommunity::routes))) {

    $controllerArr = explode('@', BetCommunity::routes[$request->route]);
    $controllerClass = $controllerArr[0];
    $method = sizeof($controllerArr) == 2 ? $controllerArr[1] : BetCommunity::DEFAULT_METHOD;
    $controllerObject = new $controllerClass($request, $envObj);

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
    
    include 'Pages/' . $data['template'];
} else{
    include './Pages/404.php'; //include error 404 page for undefined routes
 }
?>

