<?php 
    class ResultsController {
        public function __construct($request) {
            parent::__construct($request);
            $this->predictionIds = $request->query['predictionIds']; 
        }

        public function validate() {
            if (!$this->predictionIds) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid request, competition ids is required'));
            }

            $predictionIdsArr = explode(',', $this->predictionIds);

            if (!is_array($predictionIdsArr)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid competition ids'));
            }

            for ($index = 0; $index < sizeof($predictionIdsArr); $index++) {
                if (!is_numeric($predictionIdsArr[$index])) {
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid competition ids'));
                }
            }

            return true;
        }

        public function perform() {
            $this->pdoConnection->open();
            $pathToFile = __DIR__ . '/../JsonData/livescores/live-scores.json';
            $pathToTimestamp = __DIR__ . '/../JsonData/livescores/timestamp.txt';

            if (file_exists($pathToFile) && $this->getMinutesDiffFromNow(file_get_contents($pathToTimestamp)) < 15) {
                $data = json_decode(file_get_contents($pathToFile));
            } else {
                $data = file_get_contents('http://livescore-api.com/api-client/scores/live.json?key=I6AUQWWnzLs6X5Jp&secret=EsdilZDQwoq6EpLnvmhmjeJSZcZXiImW');
            }

            $endedMatches = $this->getEndedMatches($data);
            $predictions = PredictionMoedl::getPredictionsByIds($this->pdoConnection, $this->predictionIds);

            if (!$prediction) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            }

            $scores = $this->getUpdatedScores($predictions, $endedMatches);
            $this->jsonResponse(array('success' => true, 'data' => $scores));
        }

        public function getMinutesDiffFromNow($dateStr){
            $startDate = new DateTime($dateStr);
            $sinceStart = $startDate->diff(new DateTime(gmdate("Y-m-d\ H:i:s")));
    
            $minutes = $sinceStart->days * 24 * 60;
            $minutes += $sinceStart->h * 60;
            $minutes += $sinceStart->i;
            
            return $minutes;
        }


        public function getEndedMatches($data) {
            $endedMatches = array();
            foreach($data->data->match as $match) {
                if ($match->status == 'FINISHED') 
                    array_push($endedMatches, $match);
            }
        }

        public function getUpdatedScores($predictions, $endedMatches) {
            $updatedScores = array();
            foreach($predictions as $prediction) {
                $predictionObj = json_decode($prediction['prediction']);
        
                if (property_exists($predictionObj, 'competition_ids')) {
                    $scores = property_exists($predictionObj, 'scores') ? $predictionObj->scores : [];
                    for ($index = sizeof($scores); $index < sizeof($predictionObj->competition_ids); $index++) {
                        $competitionId = $predictionObj->competition_ids[$index];
                        $teamIDs = explode('_', $predictionObj->team_ids[0]);
                        $homwId = $teamIDs[0];
                        $awayId = $teamIDs[1];
                        
                        // Checks if game has not started or in progress
                        if ($predictionObj->dates[$index] > gmdate("Y-m-d\ H:i:s") || getMinutesDiffFromNow($predictionObj->dates[$index]) < 90)
                            continue;
                        
                        foreach($endedMatches as $match) {
                            if ($math->home_id == $match->home_id && $match->away_id == $awayId) {
                                array_push($scores, $match->ft_score);
                                break;
                            }
                        }
                    }
                    array_push($updatedScores, array('id' => $prediction['id'], 'scores' => $scores));

                } else if(property_exists($predictionObj, 'dates')) {
                    $scores = property_exists($predictionObj, 'scores') ? $predictionObj->scores : [];
                    for ($index = sizeof($scores); $index < sizeof($predictionObj->competition_ids); $index++) {
                        $dateArr = explode(' ', $predictionObj->dates[$index]);
                        $fixture = explode(' - ', $predictionObj->fixtures[$index]);
        
                        // Checks if game has not started or in progress
                        if ($predictionObj->dates[$index] > gmdate("Y-m-d\ H:i:s") || getMinutesDiffFromNow($predictionObj->dates[$index]) < 90)
                            continue;
                        
                        foreach($endedMatches as $match) {
                            if ($dateArr[0] . ':' . $dateArr[1] == $match->scheduled && isMatch($match->home_name, $match->away_name, $fixture)) {
                                array_push($scores, $match->ft_score);
                                break;
                            }
                        }
                    }
                    array_push($updatedScores, array('id' => $prediction['id'], 'scores' => $scores));
                }
            }

            return $updatedScores;
        }
    }
?>
