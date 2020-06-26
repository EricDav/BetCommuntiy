<?php
    include __DIR__  . '/../Enviroment/Enviroment.php';
    include __DIR__  . '/../Config/Config.php';
    include __DIR__  . '/../DB/DBConnection.php';

    function getMinutesDiffFromNow($dateStr){
        $startDate = new DateTime($dateStr);
        $sinceStart = $startDate->diff(new DateTime(gmdate("Y-m-d\ H:i:s")));

        $minutes = $sinceStart->days * 24 * 60;
        $minutes += $sinceStart->h * 60;
        $minutes += $sinceStart->i;
        
        return $minutes;
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
        
        $fromAPiArr = explode(' ', $fromApi);
        $fromBookingArr = explode(' ', $fromBooking);

        if (sizeof($fromAPiArr) == 1) {
            return strpos($fromApi, $fromBooking) !== false || strpos($fromApi, $fromBooking) !== false;
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
    // $data = json_decode(file_get_contents('http://livescore-api.com/api-client/scores/live.json?key=I6AUQWWnzLs6X5Jp&secret=EsdilZDQwoq6EpLnvmhmjeJSZcZXiImW'));
    $data = json_decode(file_get_contents(__DIR__ . '/../data.json'));
    
    // file_put_contents('data.json', json_encode($data));
    $endedMatches = array();

    foreach($data->data->match as $match) {
        if ($match->status == 'FINISHED')
            array_push($endedMatches, $match);
    }


    if (!$data->data->match || sizeof($data->data->match) == 0) {
        echo $counter;
        $counter +=1;
        // sleep(1800);
       //  continue;
    }

    $sql = 'SELECT id, prediction FROM predictions WHERE scores_finished=0';
    try {
        $predictions = $pdoConnection->pdo->query($sql)->fetchAll();
    } catch (Exception $e) {
        var_dump($e);
        // Log error;
    }

    // var_dump($endedMatches);  exit;
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
                   // var_dump($match); exit;
                    if ($match->home_id == $homeId && $match->away_id == $awayId) {
                        array_push($scores, $match->ft_score);
                        echo 'Break';
                        break;
                    }
                }
            }
            // var_dump($scores);
            $predictionObj->scores = $scores;
            $pdoConnection->pdo->query('UPDATE predictions SET prediction='. "'" . json_encode($predictionObj) . "'" . ' WHERE id=' . $prediction['id']);
        } else if(property_exists($predictionObj, 'dates')) {
            for ($index = sizeof($scores); $index < sizeof($predictionObj->dates); $index++) {
                $dateArr = explode(' ', $predictionObj->dates[$index]);
                $fixture = explode(' - ', $predictionObj->fixtures[$index]);

                // Checks if game has not started or in progress
                if ($predictionObj->dates[$index] > gmdate("Y-m-d\ H:i:s") || getMinutesDiffFromNow($predictionObj->dates[$index]) < 90)
                    continue;
                
                foreach($endedMatches as $match) {
                    if ($dateArr[1] == $match->scheduled) {
                        array_push($scores, $match->ft_score);
                    }

                    if ($dateArr[0] . ':' . $dateArr[1] == $match->scheduled && isMatch($match->home_name, $match->away_name, $fixture)) {
                        array_push($scores, array($fixture => $match->ft_score));
                        break;
                    }
                }
            }
            var_dump($scores);
            $predictionObj->scores = $scores;
            $pdoConnection->pdo->query('UPDATE predictions SET prediction='. "'" . json_encode($predictionObj) . "'" . ' WHERE id=' . $prediction['id']);
        }
    }
    // echo $counter;
    // $counter +=1;
    // sleep(1800);

    // while (true) {
    //     if (!$data) {
    //         // TODO
    //         break;
    //         // log error like time
    //     }

    //     if (!$data->data->next_page) {
    //         break;
    //     }
                    
    //     $data = json_decode(file_get_contents($data->data->next_page));
    //     array_push($results, $data->data->match);
    //     $page+=1;
    // }
  //  }

?>