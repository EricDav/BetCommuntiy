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
$request = new Request(); // Create a request object
$data = null;


if (BetCommunity::routes[$request->route] != null) {
     $controllerClass = BetCommunity::routes[$request->route];
     $controllerObject = new $controllerClass($request);

    if ($controllerObject->validate()) {
        $controllerObject->perform();
    }
}
?>

<html class="js sizes customelements history pointerevents postmessage webgl websockets cssanimations csscolumns csscolumns-width csscolumns-span csscolumns-fill csscolumns-gap csscolumns-rule csscolumns-rulecolor csscolumns-rulestyle csscolumns-rulewidth csscolumns-breakbefore csscolumns-breakafter csscolumns-breakinside flexbox picture srcset webworkers" lang="en">
<head>
  
</head>
<body>
    <div id="spinner-wrapper" style="display: none;">
      <div class="spinner"></div>
    </div>
</body>
</html>

