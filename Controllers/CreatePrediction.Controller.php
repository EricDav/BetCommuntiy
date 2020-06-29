<?php 
    class CreatePredictionController extends Controller {
        public function __construct($request) {
            parent::__construct($request);
            $this->startDateTime = isset($request->postData['start_date_time']) ? $request->postData['start_date_time'] : '';
            $this->endDateTime = isset($request->postData['end_date_time']) ? $request->postData['end_date_time'] : '';
            $this->prediction = $this->request->postData['prediction'];
            $this->totalOdds =  '0';
            $this->type = $request->postData['type'] ? $request->postData['type'] : '';
        }

        public function validate() {
            $this->authenticate();

            // Validate odds
            if (!is_numeric($this->totalOdds)) {
                $this->error['odds'] = 'Invalid odd odds should be in numbers';
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

                // if ($this->type == BetGamesController::PLATFORM_BET9JA) {
                //     if (!$this->getDatesForBet9jaBooking($this->prediction)) {
                //         $this->error['prediction'] = 'Something is wrong with prediction json';
                //     }
                // }

                if (sizeof($this->error) == 0 && $this->type == 'fixtures') {
                    if($this->validateFixtureJson($this->prediction)['']) {
                        $this->error['prediction'] = 'Something is wrong with prediction json';
                    }
                }
            }

            if (sizeof($this->error) == 0)
                return true;

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => $this->error));

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
            $approved = $this->type == BetGamesController::PLATFORM_BET9JA ? 0 : 1;
            $userId = $this->request->session['userInfo']['id'];
            $result = PredictionModel::createPrediction($this->pdoConnection, $this->startDateTime, $this->endDateTime, $userId, $this->totalOdds, 
                $this->prediction, $approved, $this->type);

            if ($result)
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'messages' => 'Prediction created successfully'));

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
        }


        /**
         * It checks weather a data is a valid json
         * 
         * @param $data the json to validate
         */
        function jsonValidator($data) {
            if (!empty($data)) {
                @json_decode($data);
                return (json_last_error() === JSON_ERROR_NONE);
            }
            return false;
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
                if (array_key_exists($competitionsFixturesFromFile, $competitionIdFromClient)) {
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