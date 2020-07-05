<?php
    include __DIR__  . '/../Enviroment/Enviroment.php';
    include __DIR__  . '/../Config/Config.php';
    include __DIR__  . '/../DB/DBConnection.php';
    
    mail("alienyidavid4christ@gmail.com","My Script Verify",'Inside script');
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
            // var_dump($score);
            // var_dump($fixture);
            var_dump(property_exists($score, $fixture));
            // exit;
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

        
        // var_dump($fromAPiArr);
        // var_dump($fromBookingArr);

        if (sizeof($fromAPiArr) == 1 || sizeof($fromBookingArr) == 1) {
            return strpos($fromApi, $fromBooking) !== false || strpos($fromBooking, $fromApi) !== false;
        }

        if (sizeof($fromAPiArr) != sizeof($fromBookingArr)) {
            return false;
        }

        for($i = 0; $i < sizeof($fromBookingArr); $i++) {
            // echo '<<<<=======>>>';
            // var_dump($fromAPiArr[$i]);
            // var_dump($fromBookingArr[$i]);
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
       // var_dump($isSubStringForHome);
        $isSubStringForAway = checkTeamMatch($awayName, $homeAwayArr[1]); // || strpos($homeAwayArr[1], $awayName);
        
        if ($isSubStringForHome && $isSubStringForAway)
            return true;
        
        return false;
    }

    $pdoConnection = new PDOConnection();
    $pdoConnection->open();
    $counter = 0;
   // while (true) {
    $page = 0;
    $liveScroes = array();
    $data = json_decode(file_get_contents('http://livescore-api.com/api-client/scores/live.json?key=Smmc7JtHTTNqcoAN&secret=kSQahwWDPT2qm6AfsXrAGVhAO511tCON'));
    // var_dump($data);
    // $data = json_decode(file_get_contents(__DIR__ . '/../data2.json'));
    
    file_put_contents('data4.json', json_encode($data));
    $endedMatches = array();

    foreach($data->data->match as $match) {
        if ($match->status == 'FINISHED')
            array_push($endedMatches, $match);
    }


    if (!$data->data->match || sizeof($endedMatches) == 0) {
        echo $counter;
        $counter +=1;
        // sleep(1800);
       //  continue;
       exit;
    }

    $sql = 'SELECT id, prediction FROM predictions WHERE scores_finished=0';
    try {
        $predictions = $pdoConnection->pdo->query($sql)->fetchAll();
    } catch (Exception $e) {
        exit($e->getMessage());
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
                        break;
                    }
                }
            }
            $predictionObj->scores = $scores;
            $pdoConnection->pdo->query('UPDATE predictions SET prediction='. "'" . json_encode($predictionObj) . "'" . ' WHERE id=' . $prediction['id']);
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
                
                foreach($endedMatches as $match) {
                    if ($dateArr[1] == $match->scheduled && isMatch($match->home_name, $match->away_name, $fixture)) {
                        array_push($scores, array($predictionObj->fixtures[$index] => $match->ft_score));
                    }
                }
            }

            $predictionObj->scores = $scores;
            if (sizeof($predictionObj->dates) == sizeof($predictionObj->scores)) {
                $pdoConnection->pdo->query('UPDATE predictions SET scores_finished=1 WHERE id=' . $prediction['id']);
            }
            $pdoConnection->pdo->query('UPDATE predictions SET prediction='. "'" . json_encode($predictionObj) . "'" . ' WHERE id=' . $prediction['id']);
        }
    }

    exit(1);
 // }
?>
