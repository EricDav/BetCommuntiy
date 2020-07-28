<?php
    include __DIR__  . '/../Enviroment/Enviroment.php';
    include __DIR__  . '/../Config/Config.php';
    include __DIR__  . '/../DB/DBConnection.php';
    include __DIR__ . '/../SendMail.php';
    include __DIR__ . '/cronEmailHelper.php';
    include __DIR__  . '/../ErrorMail.php';
    include __DIR__  . '/../BetCommunity.Class.php';

    $envObj = json_decode(file_get_contents(__DIR__ .'/../.envJson'));
    
    mail("alienyidavid4christ@gmail.com","My subject",'Test');

    function getMinutesDiffFromNow($dateStr){
        $startDate = new DateTime($dateStr);
        $sinceStart = $startDate->diff(new DateTime(gmdate("Y-m-d\ H:i:s")));

        $minutes = $sinceStart->days * 24 * 60;
        $minutes += $sinceStart->h * 60;
        $minutes += $sinceStart->i;
        
        return $minutes;
    }

    function checkFixture($fixture, $scores) {
        foreach($scores as $score) {
            // var_dump($score); var_dump($fixture); echo '|';
            if (property_exists($score, $fixture)) {
                return true;
            }
        }

        return false;
    }

    /**
     * This function takes two team from two 
     * different platform API and boking number and try 
     * to do some matching if they are the same.
     * 
     * E.g Manchester City && Man City should return true 
     * for the above instance.
     */
    function checkTeamMatch($fromApi, $fromBooking) {
        if ($fromApi == $fromBooking) 
            return true;
        
        // return strpos($fromApi, $fromBooking) !== false || strpos($fromBooking, $fromApi) !== false;

        $fromAPiArr = explode(' ', $fromApi);
        $fromBookingArr = explode(' ', $fromBooking);

        // Loop to find empty elements and 
        // unset the empty elements 
        foreach($fromAPiArr as $key => $value)          
            if(empty($value)) 
                unset($fromAPiArr[$key]);

        foreach($fromBookingArr as $key => $value)          
            if(empty($value))
                unset($fromBookingArr[$key]);

        

        if (sizeof($fromAPiArr) == 1 || sizeof($fromBookingArr) == 1) {
            return strpos($fromApi, $fromBooking) !== false || strpos($fromBooking, $fromApi) !== false;
        }

        if (sizeof($fromAPiArr) != sizeof($fromBookingArr)) {
            return false;
        }

        for($i = 0; $i < sizeof($fromBookingArr); $i++) {
            if (strpos($fromAPiArr[$i], $fromBookingArr[$i]) !== false || strpos($fromBookingArr[$i], $fromAPiArr[$i]) !== false) {
                continue;
            }

            return false;
        }

        return true;
    }

    function isMatch($homeName, $awayName, $homeAwayArr) {
        if ($homeName == $homeAwayArr[0] && $awayName == $homeAwayArr[1]) {
            true;
        }

        $isSubStringForHome = checkTeamMatch($homeName, $homeAwayArr[0]);
        $isSubStringForAway = checkTeamMatch($awayName, $homeAwayArr[1]); // || strpos($homeAwayArr[1], $awayName);
        
        if ($isSubStringForHome && $isSubStringForAway)
            return true;
        
        return false;
    }

    $pdoConnection = new PDOConnection();
    $pdoConnection->open();
    $counter = 0;
    $page = 0;
    $liveScroes = array();

    // $jsonData = file_get_contents('http://livescore-api.com/api-client/scores/live.json?key='. $envObj->LIVESCORE_API_KEY . '&secret=' . $envObj->LIVESCORE_API_SECRET);
    $jsonData = file_get_contents(__DIR__ . "/G.json");
    $data = json_decode($jsonData);
    file_put_contents(__DIR__ . "/G.json", $jsonData);
    
    if (!$data->data->match) {
        mail("alienyidavid4christ@gmail.com","My subject", "API return empty or fail");
        ErrorMail::Log('updateScores.php', '110', 'It seems livescores API failed or returns empty result');
        exit(-1);
    }

    $endedMatches = array();

    foreach($data->data->match as $match) {
        if ($match->status == 'FINISHED')
            array_push($endedMatches, $match);
    }

    // var_dump($endedMatches); exit;

    if (sizeof($endedMatches) == 0) {
        ErrorMail::Log('updateScores.php', '110', 'Empty ended matches');
        exit(0);
    }

    $sql = 'SELECT predictions.id, created_at, type, is_each_game_update, is_all_game_update, predictions.created_at, users.email, predictions.prediction FROM predictions INNER JOIN users ON predictions.user_id=users.id WHERE scores_finished=0';
    try {
        $predictions = $pdoConnection->pdo->query($sql)->fetchAll();
    } catch (Exception $e) {
        ErrorMail::LogError($e);
    }

    foreach($predictions as $prediction) {
        $predictionObj = json_decode($prediction['prediction']);
        $scores = property_exists($predictionObj, 'scores') ? $predictionObj->scores : [];

        if (property_exists($predictionObj, 'competition_ids')) {
            for ($index = sizeof($scores); $index < sizeof($predictionObj->competition_ids); $index++) {
                $competitionId = $predictionObj->competition_ids[$index];
                $teamIDs = explode('-', $predictionObj->team_ids[$index]);
                $homeId = $teamIDs[0];
                $awayId = $teamIDs[1];
                
                // Checks if game has not started or in progress
                if ($predictionObj->dates[$index] > gmdate("Y-m-d\ H:i:s") || getMinutesDiffFromNow($predictionObj->dates[$index]) < 90) {
                    continue;
                }
                
                foreach($endedMatches as $match) {
                    if ($match->home_id == $homeId && $match->away_id == $awayId) {
                        array_push($scores, array($predictionObj->fixtures[$index] => $match->ft_score));
                        if ($prediction['is_each_game_update'] == 1) {
                            genererateNotificationEmailHtml($match, $prediction['created_at'], $prediction['type'], $predictionObj->bet_code, $index, $prediction['email'],$prediction['id']);
                            // send email notification
                        }
                        break;
                    }
                }
            }

            $predictionObj->scores = $scores;
            $pdoConnection->pdo->query('UPDATE predictions SET prediction='. "'" . json_encode($predictionObj) . "'" . ' WHERE id=' . $prediction['id']);
            if (sizeof($predictionObj->dates) == sizeof($predictionObj->scores)) {
                $pdoConnection->pdo->query('UPDATE predictions SET scores_finished=1 WHERE id=' . $prediction['id']);
                genererateNotificationEmailHtmlForAll($predictionObj, $prediction['created_at'], $prediction['type'], $prediction['email'], $prediction['id']);
            }
        } else if(property_exists($predictionObj, 'dates')) {
            for ($index = 0; $index < sizeof($predictionObj->dates); $index++) {

                // checks if fixture scores has been updated.
                if (checkFixture($predictionObj->fixtures[$index], $scores))
                    continue; 
                $dateArr = explode(' ', $predictionObj->dates[$index]); // Get's the date arr e.g ['2020-07-01', '6:20'] 
                $fixture = explode(' - ', $predictionObj->fixtures[$index]); // Get's the fixtures in array e.g ['Chelsea', 'Barca']
                

                // Checks if game has not started or in progress
                if ($predictionObj->dates[$index] > gmdate("Y-m-d\ H:i:s") || getMinutesDiffFromNow($predictionObj->dates[$index]) < 90)
                    continue;
                $counter = 0;
                foreach($endedMatches as $match) {
                    if ($dateArr[1] == $match->scheduled && isMatch($match->home_name, $match->away_name, $fixture)) {
                        
                        // mail("alienyidavid4christ@gmail.com","My subject", "I got something!!!");
                        array_push($scores, array($predictionObj->fixtures[$index] => $match->ft_score));
                        mail("alienyidavid4christ@gmail.com","My subject", json_encode($scores));
                        if ($prediction['is_each_game_update'] == 1) {
                            mail("alienyidavid4christ@gmail.com","My subject", "InIn");
                            // send email notification
                            genererateNotificationEmailHtml($match, $prediction['created_at'], $prediction['type'], $predictionObj->bet_code, $index, $prediction['email'],  $prediction['id']);
                            mail("alienyidavid4christ@gmail.com","My subject", "Sent email");
                        }
                    }
                    $counter+=1;
                }
            }

            $predictionObj->scores = $scores;
            $pdoConnection->pdo->query('UPDATE predictions SET prediction='. "'" . json_encode($predictionObj) . "'" . ' WHERE id=' . $prediction['id']);
            // mail("alienyidavid4christ@gmail.com","My subject", "Updated shit");
            if (sizeof($predictionObj->dates) == sizeof($predictionObj->scores)) {
                $pdoConnection->pdo->query('UPDATE predictions SET scores_finished=1 WHERE id=' . $prediction['id']);
                genererateNotificationEmailHtmlForAll($predictionObj, $prediction['created_at'], $prediction['type'], $prediction['email'], $prediction['id']);
            }
        }
    }

    exit(1);
?>
