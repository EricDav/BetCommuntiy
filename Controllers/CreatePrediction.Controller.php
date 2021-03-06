<?php 
    class CreatePredictionController extends Controller {
        public function __construct($request, $envObj) {
            parent::__construct($request);
            $this->envObj = $envObj;
            $this->startDateTime = isset($request->postData['start_date_time']) ? $request->postData['start_date_time'] : '';
            $this->endDateTime = isset($request->postData['end_date_time']) ? $request->postData['end_date_time'] : '';
            $this->prediction = $this->request->postData['prediction'];
            $this->totalOdds =  '0';
            $this->type = isset($request->postData['type']) ? $request->postData['type'] : '';
            $this->currentDate = isset($request->postData['current_date']) ? $request->postData['current_date'] : null;
            $this->getEachGameUpdate = isset($request->postData['get_each_game_update'])? $request->postData['get_each_game_update']:0;
            $this->getAllGameUpdate = isset($request->postData['get_all_game_update'])? $request->postData['get_all_game_update']:0;
        }

        public function validate() {
            $this->authenticate();

            /**
             * Validate get game update request
             */
            $getUpdateValues = [
                'get_each_game_update' => $this->getEachGameUpdate,
                'get_all_game_update' => $this->getAllGameUpdate
            ];
            
            foreach($getUpdateValues as $index => $getUpdateValue){
                $result = $this->isGetGameUpdateValid($getUpdateValue);
                if(!$result['is_numeric'])
                    $this->error[$index] = 'Invalid get game update request';

                else if(!$result['is_with_in_range'])
                    $this->error[$index] = 'Invalid get game update request';
            }
            

            if (!$this->startDateTime) {
                $this->error['start_date_time'] = 'Start date time is required';
            }

            if (!$this->endDateTime) {
                $this->error['end_date_time'] = 'End date time is required';
            }

            // Validate date and time
            $startDateTimeArr = explode(' ', $this->startDateTime);
            $startDate = $startDateTimeArr[0];
            $startTime = $startDateTimeArr[1];

            $endDateTimeArr = explode(' ', $this->endDateTime);
            $endDate = $endDateTimeArr[0];
            $endTime = $endDateTimeArr[1];

            if (!$this->isValidateDateTime($startDate, $startTime)) {
                $this->error['start_date_time'] = 'Invalid game start date time';
            }

            /**
             * Validates current date
             */
            if (!$this->currentDate) { // checks if currentdate is added
                $this->error['current_date'] = 'Current date is required';
            } else {
                $currentDateTime = explode(' ', $this->currentDate);
                if (!$this->isValidateDateTime($currentDateTime[0], $currentDateTime[1])) {
                    $this->error['current_date'] = 'Invalid current date';
                }
            }

            if (!$this->isValidateDateTime($endDate, $endTime)) {
                $this->error['end_date_time'] = 'Invalid game end date time';
            }

            if ($startDate . ' ' . $startTime > $endDate . ' ' . $endTime) {
                $this->error['date_time'] = 'Start game date can not be bigger than end date';
            }

            if (empty(trim($this->type))) {
                $this->error['type'] = 'Method type is required';
            }

            if (empty(trim($this->prediction))) {
                $this->error['prediction'] = 'Prediction is required';
            } else if (!$this->jsonValidator($this->prediction)) {
                $this->error['prediction'] = 'Invalid prediction.';
            } else if ($this->startDateTime && $this->type != BetGamesController::PLATFORM_BET9JA &&  gmdate("Y-m-d\ H:i:s") > $this->startDateTime) {
                $this->error['prediction'] = 'At least the first game has begun.';
            } else {
                if (sizeof($this->error) == 0 && $this->type == BetGamesController::PLATFORM_BETKING) {
                    if (!$this->validateBetKingPredictionJson($this->prediction)['success']) {
                        $this->error['prediction'] = 'Something is wrong with prediction json';
                    }
                }

                if (sizeof($this->error) == 0 && $this->type == BetGamesController::PLATFORM_SPORTY_BET) {
                    if (!$this->validateSportyBetPredictionJson($this->prediction)['success']) {
                        $this->error['prediction'] = 'Something is wrong with prediction json';
                    }
                }

                if ($this->type == BetGamesController::PLATFORM_BET9JA) {
                    // echo 'Here'; exit;
                    $bet9jaIsValid = $this->validateBet9jaPredictionJson($this->prediction);
                    // var_dump($bet9jaIsValid); exit;
                    if (!$bet9jaIsValid['success']) {
                        $this->error['prediction'] = $bet9jaIsValid['message'];
                    } else {
                        $predictionData = json_decode($this->prediction);
                        $predictionData->dates = $bet9jaIsValid['date'];
                        $this->prediction = json_encode($predictionData);
                    }
                }

                if (sizeof($this->error) == 0 && $this->type == 'fixtures') {
                    if(!$this->validateFixtureJson($this->prediction)['success']) {
                        $this->error['prediction'] = 'Something is wrong with prediction json';
                    }
                }
            }

            if (sizeof($this->error) == 0)
                return true;

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => $this->error));

        }

        public function setStartEndDatesForBet9ja($dates) {
            $minDate = $dates[0];
            $maxDate = $date[0];

            for ($i = 0; $i < sizeof($dates); $i++) {
                $cDate = $dates[$i];
                if ($cDate > $maxDate) {
                    $maxDate = $cDate;
                }

                if ($cDate < $minDate) {
                    $minDate = $cDate;
                }
            }
            $this->startDateTime = $minDate;
            $this->endDateTime = $maxDate;
        }


        public function isGetGameUpdateValid($value){
            $__isNumeric = false;
            $__isWithinRange = false;

            if(is_numeric($value))
                $__isNumeric = true;
            
            if(strlen($value) == 1){
                if(preg_match("/[0, 1]{1}/", $value))
                    $__isWithinRange = true;
            }

            $result = [
                'is_numeric' => $__isNumeric,
                'is_with_in_range' => $__isWithinRange
            ];

            return $result;
        }


        public function getStatus($prediction) {
            if (!$prediction['won']) {
                $now = gmdate("Y-m-d\ H:i:s");

                if ($now > $prediction['created_at'])
                    return 'In progress';
                return 'Not started';
            }

            if ($prediction['won'] == 0)
                return 'Concluded Won';
            return 'Concluded Lost';
        }

        public function perform() {
            $this->pdoConnection->open();
            // var_dump($this->currentDate); exit;
            if (!$this->validatePredictionsPerDay($this->currentDate)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Maximum prediction reached for the day. You only make three predictions per day'));
            }

            $approved =  1;
            $userId = $this->request->session['userInfo']['id'];
            $result = PredictionModel::createPrediction($this->pdoConnection, $this->startDateTime, $this->endDateTime, $userId, $this->totalOdds, 
                $this->prediction, $approved, $this->type, $this->getEachGameUpdate, $this->getAllGameUpdate);

            if ($result) {
                $notifications = NotificationModel::creatNotifictionsForPrediction($this->pdoConnection, $this->request->session['userInfo']['id'], $result);

                if (!$notifications) {

                    // TODO LOG ERRORS HERE
                }
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'messages' => 'Prediction created successfully', 'prediction_id' => $result));
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'messages' => 'Server error'));
        }

        /**
         * It valdates prediction json. Checks if the clients 
         * sends a json that correlate to the booking number. 
         * It also validates if the booking number is valid
         * 
         * @param  $pridictionJson Json sent from the client
         */
        public function validateBetKingPredictionJson($pridictionJson) {
            $dataFromClient = json_decode($pridictionJson);
            $bookingNumber = $dataFromClient->bet_code;
            $data = json_decode(file_get_contents('https://sportsapi.betagy.services/api/BetCoupons/Booked/' . $bookingNumber . '/en'));

            if (!$data->BookedCoupon) {
                return array('success' => false, 'message' => 'Invalid booking number');
            }

            if (!$data->BookedCoupon->Odds || sizeof($data->BookedCoupon->Odds) == 0) {
                return array('success' => false, 'message' => 'All matches has began');
            }

            if (sizeof($data->BookedCoupon->Odds) != sizeof($dataFromClient->leagues)) {
                return array('success' => false, 'message' => 'Booking number does not match prediction');
            }

            $dataSize = sizeof($data->BookedCoupon->Odds);
            for ($i = 0; $i < $dataSize; $i++) {
                if ($data->BookedCoupon->Odds[$i]->MatchName != $dataFromClient->fixtures[$i]) {
                    return array('success' => false, 'message' => 'Data does not match');
                }
            }

            return array('success' => true);
        }

        public function validateBet9jaPredictionJson($pridictionJson) {
            $dataFromClient = json_decode($pridictionJson);
            $bookingNumber = $dataFromClient->bet_code;
            // var_dump($dataFromClient->fixtures); exit;

            $url = $this->envObj->API_URL . '/bet9ja/' . $bookingNumber;
            $data = json_decode(file_get_contents($url));

            if (!$data->success) {
                return array('success' => false, 'message' => $data->message);
            }

            if (sizeof($dataFromClient->fixtures) != sizeof($data->data->fixtures)) {
                return array('success' => false, 'message' => 'Data does not match');
            }

            for($i = 0; $i < sizeof($data->data->dates); $i++) {
                if (gmdate("Y-m-d\ H:i:s") > $data->data->dates[$i]) {
                    return array('success' => false, 'message' => 'Date does not match');
                }

                if (!$this->checkBet9jaMatchFixture($dataFromClient->fixtures, $data->data->fixtures[$i])) {
                    return array('success' => false, 'message' => 'Fixtures does not match');
                }
            }

            $dates = $this->getDates($dataFromClient->fixtures, $data->data->fixtures, $data->data->dates);

            $this->setStartEndDatesForBet9ja($dates);

            return array('success' => true, 'date' => $dates);
        }

        public function checkBet9jaMatchFixture($fixturesFromClient, $fixtureFromServer) {
            foreach($fixturesFromClient as $f) {
                if ($f == $fixtureFromServer)
                    return true;
            }

            return false;
        }

        public function getDates($fixturesFromClient, $fixturesFromAPI, $dates) {
            $newDates = array();

            foreach($fixturesFromClient as $fc) {
                $index = 0;
                foreach($fixturesFromAPI as $fa) {
                    if ($fc == $fa) {
                        array_push($newDates, $dates[$index]);
                        break;
                    }

                    $index +=1;
                }
            }

            return $newDates;
        }

        /**
         * It valdates prediction json. Checks if the clients 
         * sends a json that correlate to the booking number. 
         * It also validates if the booking number is valid for SportyBet
         * 
         * @param  $pridictionJson Json sent from the client
         */
        public function validateSportyBetPredictionJson($pridictionJson) {
            $dataFromClient = json_decode($pridictionJson);
            $bookingNumber = $dataFromClient->bet_code;
            $data = json_decode(file_get_contents('https://www.sportybet.com/api/ng/orders/share/' . $bookingNumber));

            if (!$data->innerMsg == 'invalid') {
                return array('success' => false, 'message' => $data->message);
            }

            if (sizeof($data->data->outcomes) != sizeof($dataFromClient->leagues)) {
                return array('success' => false, 'message' => 'Booking number does not match prediction');
            }

            $dataSize = sizeof($data->data->outcomes);
            for ($i = 0; $i < $dataSize; $i++) {
                $item = $data->data->outcomes[$i];
                if ($item->matchStatus != 'Not start')
                    return array('success' => false, 'message' => 'At least one match has begun');
                $fixture = $item->homeTeamName . ' - ' . $item->awayTeamName;

                if ($fixture != $dataFromClient->fixtures[$i]) {
                    return array('success' => false, 'message' => 'Data doesn not match');
                }
            }

            return array('success' => true);
        }

        public function validateFixtureJson($predictionJson) {
            $competitionFixtures = array();
            $competitionIds = array();
            $prediction = json_decode($predictionJson);
            
            $fixturesFromClient = $prediction->fixtures;
            $datesFromClient = $prediction->dates;
            $competitionIdsFromClient = $prediction->competition_ids;
            $teamIds = $prediction->team_ids;
            $competitionsFixturesFromFile = array();
            

            for($i = 0; $i < sizeof($fixturesFromClient); $i++) {
                $competitionIdFromClient = $competitionIdsFromClient[$i];
                
                /**
                 * Get fixtures from file from the competition id
                 * Checks the array of fixtures if we have already load it
                 * Else fecth it from file and add to the array
                 */
                if (array_key_exists($competitionIdFromClient, $competitionsFixturesFromFile)) {
                    $competitionFixturesFromFile = $competitionsFixturesFromFile[$competitionIdFromClient];
                } else {
                    $competitionFixturesFromFile = $this->getFixturesFromFile($competitionIdFromClient);
                }

                $fixtureFromClient = $fixturesFromClient[$i];

                if (!$competitionFixturesFromFile) 
                    return array('success' => false, 'message' => 'Invalid prediction');

                if (gmdate("Y-m-d\ H:i:s") >= $datesFromClient[$i]) {
                    return array('success' => false, 'message' => 'A game has started');
                }

                foreach($competitionFixturesFromFile as $fixtureFromFile) {
                    $isValid = false;
                    foreach($fixtureFromFile->fixtures as $fixture) {
                        $teamNames = explode(' - ', $fixtureFromClient);
                        if ($teamNames[0] === $fixture->home_name && $teamNames[1] === $fixture->away_name && 
                            $datesFromClient[$i] === $fixture->date . ' ' . $fixture->time && $teamIds[$i] === $fixture->home_id . '-' . $fixture->away_id ) {
                            // echo 'I got here';
                            $isValid = true;
                            break;
                        }
                    }

                    if ($isValid) {
                        break;
                    }

                    if (!$isValid) {
                        return array('success' => false, 'message' => 'Prediction data is not correct');
                    }
                }
            }

            return array('success' => true);

        }

        public function getFixturesFromFile($competitionId) {
            $filePath = __DIR__ . '/../JsonData/fixtures/competition-' . $competitionId . '/fixture-competition-' . $competitionId . '.json';
            if (!file_exists($filePath))  {
                return false;
            }

            return json_decode(file_get_contents($filePath));
        }

        /**
         * Function that determines if a user has made the maximum 
         * match per day.
         * 
         * @param $predictionsPerDay - The maximum number of predictions per day
         * @return It returns true if the logged in user has not gotten 
         * to the maximum prediction for the day else returns false
         * 
         */
        public function validatePredictionsPerDay($currentDateTimeInClientTimeZone) {
            // var_dump($currentDateTimeInClientTimeZone); exit;
            $maxPredictions = PredictionModel::getUsersLastPredictions($this->pdoConnection, $this->request->session['userInfo']['id'],
                BetCommunity::NUM_PREDICTIONS_PER_DAY * 2);
            
            // var_dump($maxPredictions); exit;
            $maxPossibelPredictionsWithCheat = BetCommunity::NUM_PREDICTIONS_PER_DAY * 2;
            
            if (sizeof($maxPredictions) < 3) {
                return true;
            }

            
            if (sizeof($maxPredictions) == $maxPossibelPredictionsWithCheat) {
                $firstDate = explode(' ', $maxPredictions[0]['created_at'])[0];
                $sixthDate = explode(' ', $maxPredictions[5]['created_at'])[0];
    
                if (explode('-', $firstDate)[2] == explode('-', $sixthDate)[2]) {
                    echo 'Here'; exit;
                    // TODO log error here should not get to this
                    $msg = "Something unexpected happened at line 346 file: CreatePrediction.Controller.php";
                    mail(BetCommunity::DEFAULT_TODO_LOG_EMAIL,"Error Log",$msg);
                    return false;
                }
            }

            $currentDateTimeInUTC = gmdate("Y-m-d\ H:i:s");

            $minutesDiff = $currentDateTimeInClientTimeZone > $currentDateTimeInUTC 
                    ? $this->getMinutesDiff($currentDateTimeInClientTimeZone, $currentDateTimeInUTC) : -1 * $this->getMinutesDiff($currentDateTimeInClientTimeZone, $currentDateTimeInUTC);

            // last num predictions per day the user made
            $thirdPreDateTimeUTC = $maxPredictions[BetCommunity::NUM_PREDICTIONS_PER_DAY - 1]['created_at']; // get created in UTC
            $time = strtotime($thirdPreDateTimeUTC) + ($minutesDiff * 60); // converting time to local
            $thirdPreDateTimeInClientTimeZone = date("Y-m-d H:i:s", $time);
            

            $thirdPreDate = explode(' ', $thirdPreDateTimeInClientTimeZone)[0];
            $currentDateInClientTimeZone = explode(' ', $currentDateTimeInClientTimeZone)[0];

            $thirdPreDateArr = explode('-', $thirdPreDate);
            $currentDateInClientTimeZoneArr = explode('-', $currentDateInClientTimeZone);

            if ($thirdPreDate[0] == $currentDateInClientTimeZoneArr[0] && $thirdPreDate[1] == $currentDateInClientTimeZoneArr[1]
                && $thirdPreDate[2] == $currentDateInClientTimeZoneArr[2]) {
                return false;
            }

            return true;
        }

        public function determineMatch($homeName, $awayName, $homeAwayArr) {
            if ($homeName == $homeAwayArr['homeTeam'] && $awayName == $homeAwayArr['awayTeam']) {
                true;
            }

            $isSubStringForHome = strpos($homeName, $homeAwayArr['homeTeam']) || strpos($homeAwayArr['homeTeam'], $homeName);
            $isSubStringForAway = strpos($awayName, $homeAwayArr['awayTeam']) || strpos($homeAwayArr['awayTeam'], $awayName);
            
            if ($isSubStringForHome && $isSubStringForAway)
                return true;
            
            return false;
        }
    }
?>
