<?php
    // echo __DIR__; exit;
    include __DIR__  . '/../Enviroment/Enviroment.php';
    include __DIR__  . '/../Config/Config.php';
    include __DIR__  . '/../DB/DBConnection.php';

    $pdoConnection = new PDOConnection();
    $pdoConnection->open();
    const BET9JA_TYPE = 'Bet9ja';

    // Fetch all predictions with bet9ja type that needs to be filled with dates
    $sql = 'SELECT * FROM predictions WHERE type=' . "'"  . BET9JA_TYPE . "'" . ' AND approved=0';
    echo $sql;
    try {

        var_dump($pdoConnection->pdo->query($sql)->fetchAll());
        // $predictions = $pdoConnection->pdo->query($sql)->fetchAll();
        // var_dump($predictions);
    } catch (Exception $e) {
        var_dump($e);
    }

    foreach ($predictions as $prediction) {
        var_dump($prediction['prediction']);
    }


    function getNewPridictionJson($oldPrediction) {
        $newPrediction = array();
    }

    // $initial = json_decode(file_get_contents('https://livescore-api.com/api-client/fixtures/matches.json?key=I6AUQWWnzLs6X5Jp&secret=EsdilZDQwoq6EpLnvmhmjeJSZcZXiImW'));
    // $homeTeam = 'Aston Villa';
    // $awayTeam = 'Chelsea';
    // $fixture;

    // $keepSearching = true;
    // $page = 1;

    // while ($keepSearching) {
    //     echo $page;
    //     foreach($initial->data->fixtures as $feat) {
    //         if ($feat->home_name == $homeTeam && $feat->away_name == $awayTeam) {
    //             $fixture = $feat;
    //             $keepSearching = false;
    //         }
    //     }

    //     if (!$initial->data->next_page) {
    //         break;
    //     }
        
    //     $initial = json_decode(file_get_contents($initial->data->next_page));
    //     $page+=1;
    // }

    // var_dump($fixture);
?>
