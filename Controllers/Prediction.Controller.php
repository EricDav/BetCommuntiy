<?php 
    class PredictionController extends Controller {
        const CLIENT_ID = '90e32c15459b9cdb5a86c6f0bd2f096c';
        public function __construct($request) {
            parent::__construct($request);
            $this->predictionId = null;
        }

        public function validate() {}

        public function perform() {}

        public function deletePrediction() {
            $this->authenticate();
            $this->pdoConnection->open();
            $this->validateDelete();

            if (PredictionModel::deletePrediction($this->pdoConnection, $this->request->postData['prediction_id'])) {
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'Prediction deleted successfully'));
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error could not delete prediction'));
        }

        public function getNum($data) {
            $newNum = '';
        
            for($i = 0; $i < strlen($data); $i++) {
                if (is_numeric($data[$i]) || $data[$i] == '.') {
                    $newNum .=$data[$i];
                }
            }
        
            return $newNum;
        }

        public function update() {
            $this->authenticateAdmin();
            $this->pdoConnection->open();
            $this->validateId();
            $scores = isset($this->request->postData['scores']) ? $this->request->postData['scores'] : null; // Scores to update;
            $outcomeResults = isset($this->request->postData['outcome_results']) ? $this->request->postData['outcome_results'] : null;

            if (!$scores) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Scores to update is required'));
            }

            if (!$outcomeResults) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'outcome results is required'));
            }

            if (!$this->jsonValidator($scores)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid json scores data'));
            }

            if (!$this->jsonValidator($outcomeResults)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid json outcome result data'));
            }

            $scores = json_decode($scores);
            $outcomeResults = json_decode($outcomeResults);

            $prediction = PredictionModel::getPredictionById($this->pdoConnection, $this->request->postData['prediction_id']);

            // check for server error 
            if ($prediction === false) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            }

            // check if prediction is found
            if (!$prediction) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_NOT_FOUND, 'messages' => 'Prediction not found'));
            }
            // var_dump($prediction); exit;

            // Checks if the first game has began
            if (gmdate("Y-m-d\ H:i:s") < $prediction['start_date'] && $prediction['type'] != 'Bet9ja') {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Can not update first game already began' ));
            }

            $predictionObj = json_decode($prediction['prediction']);

            if (!$this->isValidScores($scores, $predictionObj->fixtures)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid scores format' ));
            }

            if (!$this->isValidOutcomeResult($outcomeResults, $predictionObj->fixtures)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid outcome results format format' ));
            }

            // var_dump((array)$predictionObj->scores); exit;

            if (property_exists($predictionObj, 'scores')) {
                // Checks if scores has already been updated
                foreach($predictionObj->scores as $score) {
                    $scoreFixture = array_keys((array)$score)[0];
                    $i = $this->inArrayObj($scoreFixture, $scores);
                    if ($i) {
                        $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'The result of ' .  $scoreFixture . ' has already been updated'));
                    }
                }
            }

            if (property_exists($predictionObj, 'outcome_results')) {
                // Checks if outcome result has been updated
                foreach($predictionObj->outcome_results as $result) {
                    $resultFixture = array_keys((array)$result)[0];
                    if ($this->inArrayObj($resultFixture, $outcomeResults)) {
                        $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'The outcome result of ' .  $scoreFixture . ' has already been updated'));
                    }
                }
            }

           // var_dump(property_exists($predictionObj, 'outcome_results')); exit;
            if (property_exists($predictionObj, 'scores')) {
                foreach($scores as $score) {
                    array_push($predictionObj->scores, $score);
                }
            }

            if (!property_exists($predictionObj, 'scores') && sizeof($scores) > 0) {
                $predictionObj->scores = $scores;
            }

            if (property_exists($predictionObj, 'outcome_results')) {
                foreach($outcomeResults as $outcomeResult) {
                    array_push($predictionObj->outcome_results, $outcomeResult);
                }
            }

            if (!property_exists($predictionObj, 'outcome_results') && sizeof($outcomeResults) > 0) {
                $predictionObj->outcome_results = $outcomeResults;
            }

            if (PredictionModel::updatePrediction($this->pdoConnection, $this->request->postData['prediction_id'], json_encode($predictionObj))) {
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'Prediction updated successfully'));
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error could not update prediction'));
        }

        public function inArrayObj($delim, $objArr) {
            foreach($objArr as $obj) {
                if ($delim === array_keys((array)$obj)[0]) {
                    return true;
                }
            }

            return false;
        }

        public function validateId() {
            // checks if prediction id is sent to the sever or post request
            if (!isset($this->request->postData['prediction_id'])) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            }
            
            // checks if the prediction id is in its correct format
            if (!is_numeric($this->request->postData['prediction_id'])) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid prediction id'));
            }
        }

        public function isValidOutcomeResult($outcomeResults, $fixtures) {
            foreach($outcomeResults as $outcomeResult) {
                $outcomeResult = (array)$outcomeResult;
                $outcomeResultKey = array_keys($outcomeResult)[0]; // outcomeResultKey is the fixture whose value is the actual result

                // Checks if fixture exists
                if (in_array($outcomeResultKey, $fixtures)) {
                    $result = $outcomeResult[$outcomeResultKey];

                    // Validate that outcome result is in correct form i.e 1 or 0
                    if (!is_numeric($result) ||  ((int)$result != 1 && (int)$result != 0)) {
                        return false;
                    }
                    
                } else {
                    return false;
                }
            }

            return true;
        }

        public function isValidScores($scores, $fixtures) {
            foreach($scores as $score) {
                $score = (array)$score;
                
                $scoreKey = array_keys($score)[0];
                // var_dump($scoreKey); exit;

                // Checks if fixture exists
                if (in_array($scoreKey, $fixtures)) {
                    $resultPart = explode(' - ', $score[$scoreKey]);

                    if (sizeof($resultPart) != 2) {
                        return false;
                    }

                    // Validate that scores is in correct form i.e result from
                    // both team is a number like 2-0
                    if (!is_numeric(trim($resultPart[0])) || !is_numeric(trim($resultPart[1]))) {
                        return false;
                    }
                    
                } else {
                    return false;
                }
            }

            return true;
        }

        public function validateDelete() {
            $this->validateId();
            // Retrieve the prediction by id 
            $prediction = PredictionModel::getPredictionById($this->pdoConnection, $this->request->postData['prediction_id']);

            // check for server error 
            if ($prediction === false) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            }

            // check if prediction is found
            if (!$prediction) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_NOT_FOUND, 'messages' => 'Prediction not found'));
            }

            // Checks if the first game has began
            if (gmdate("Y-m-d\ H:i:s") > $prediction['start_date']) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Can not delete first game already began' ));
            }

            // check if user that created the prediction is the current logged in user 
            if ($this->request->session['userInfo']['id'] != $prediction['user_id']) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_UNAUTHORIZED_CODE, 'messages' => 'You are not allowed to perform this action'));
            }
        }

        public function reportPrediction() {
            $this->pdoConnection->open();
            $problem = isset($this->request->postData['problem']) ? $this->request->postData['problem'] : null;
            $note = isset($this->request->postData['note']) ? $this->request->postData['note'] : null;
            $predictionId  = isset($this->request->postData['prediction_id']) ? $this->request->postData['prediction_id'] : null;

            if (!in_array($problem, BetCommunity::BUGS)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'message' => 'Invalid problem'));
            }
            if (!$problem) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'message' => 'Invalid problem'));
            }

            if (!is_numeric($predictionId)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'message' => 'Invalid prediction id'));
            }
            $userId = $this->isLogin() ? $this->request->session['userInfo']['id'] : null;

            if (PredictionModel::createReportPrediction($this->pdoConnection, $predictionId, $problem, $note, $userId)) {
                $this->jsonResponse(array('success' => true, 'message' => 'report created succesfully', 'code' => Controller::HTTP_OKAY_CODE));
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
        }

        public function getPrediction() {
            $predictionId = isset($this->request->query['id']) ? $this->request->query['id'] : null;
            if (!$predictionId || !is_numeric($predictionId)) {
                $this->set404Page();
                return;
            }

            $this->pdoConnection->open();
            $competitions = json_decode(file_get_contents(__DIR__  . '/../JsonData/competitions.json'));
            $followers = array();

            $prediction = PredictionModel::getPrediction($this->pdoConnection, $predictionId);
            // var_dump($prediction); exit;
            if ($prediction === false) {
                // TODO 
                // replace with 500 error page
                $this->set404Page();
                return;
            }

            if (sizeof($prediction) == 0) {
                $this->set404Page();
                return;
            }

            $featuredUsers = UserModel::getFeaturedUsers($this->pdoConnection);
            if ($this->isLogin()) {
                $followers = UserModel::getFollowers($this->pdoConnection, $this->request->session['userInfo']['id']);
            }

            //echo 'Here'; exit;
            $this->data['supportedBettingPlatforms'] = BetGamesController::SUPPORTED_BETTING_PLATFORMS;
            $this->data['competitions'] = $competitions->data->competition;
            $this->data['followers'] = $followers;
            $this->data['prediction'] = $prediction;

            $this->setDateCreatedUTC($prediction);
            $this->setPredictionInfo($prediction);
            $this->data['featuredUsers'] = $featuredUsers;
            $this->data['template'] = 'Predictions.php';
            $this->data['title'] = '4CastBet | Prediction';
            $this->data['outcomes'] = BetCommunity::OUTCOMES;
            $this->responseType = 'html';

        }

        public function updateWonStatus() {
            $this->authenticateAdmin();
            $this->pdoConnection->open();
            $predictionId = isset($this->request->postData['prediction_id']) ? $this->request->postData['prediction_id'] : null;
            $wonStatus = isset($this->request->postData['status']) ? $this->request->postData['status'] : null;

            if ($predictionId == null || !is_numeric($predictionId)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'message' => 'Invalid prediction id'));
            }

            if ($wonStatus == null || !is_numeric($wonStatus)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'message' => 'Invalid status'));
            }

            if ((int)$wonStatus !== 1 && (int)$wonStatus !== 0) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'message' => 'Invalid status'));
            }

            // Retrieve the prediction id 
            $prediction = PredictionModel::getPredictionById($this->pdoConnection, $predictionId);

            // check for server error 
            if ($prediction === false) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error retrieving prediction'));
            }
            
            // check if prediction is found
            if (!$prediction) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_NOT_FOUND, 'messages' => 'Prediction not found'));
            }

            // Check if prediction has been updated
            if (!is_null($prediction['won'])) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Prediction already updated'));
            }

            if (PredictionModel::updatePredictionStatus($this->pdoConnection, $predictionId, $wonStatus, $this->request->session['userInfo']['id'])) {
                $this->jsonResponse(array('success' => true, 'message' => 'Status updated succesfully', 'code' => Controller::HTTP_OKAY_CODE));
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error when updating status'));
        }

        public function like() {
            $this->authenticate();
            $this->pdoConnection->open();
            // var_dump($_POST); exit;
            $predictionId = isset($this->request->postData['prediction_id']) ? $this->request->postData['prediction_id'] : null;
            $userId = $this->request->session['userInfo']['id'];

            if (!$predictionId) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_NOT_FOUND, 'message' => 'Prediction id is required'));
            }

            $like = UserModel::getLike($this->pdoConnection, $predictionId, $userId);
            // var_dump($like); exit;

            if ($like === 'Server error') {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error retrieving like'));
            }

            if ($like) {
                if (UserModel::unlike($this->pdoConnection, $predictionId, $userId)) {
                    $this->jsonResponse(array('success' => true, 'message' => 'unlike', 'code' => Controller::HTTP_OKAY_CODE));
                }
            } else {
                if (UserModel::like($this->pdoConnection, $predictionId, $userId)) {
                    $this->jsonResponse(array('success' => true, 'message' => 'like', 'code' => Controller::HTTP_OKAY_CODE));
                }
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error liking or unliking'));
        }

        public function getBalance($pdoConnection, $phoneNumber) {
            $balanceObj = SmsModel::getBalance($pdoConnection, $phoneNumber);
            if (!$phoneNumber || !is_numeric($phoneNumber)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST, 'message' => 'Invalid phone number'));
            }
            if (!$balanceObj) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_NOT_FOUND, 'message' => 'Phone number not registered'));
            }

            if ((int)$balanceObj->credits == 0) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST, 'message' => 'You did not have any credit'));
            }

            return $balanceObj->credits;
        }

        /**
         * It get the game info from a bet9ja betslip
         * game info that includes fixtures, odds, dates, etc.
         * 
         * It handles the route 
         */
        public function getGamesFromBet9jaBetslip() {
            include_once __DIR__ . '/../simplehtmldom/simple_html_dom.php';

            $betslip = $this->request->query['betslip'] ? $this->request->query['betslip'] : null;
            $slipPos = $this->request->query['pos'] ? explode(',', $this->request->query['pos']) : null;
            $clientID = $this->request->query['client_id'] ?  $this->request->query['client_id'] : null;
            $phoneNumber = $this->request->query['phone_number'] ?  $this->request->query['phone_number'] : null;

            $this->pdoConnection->open();
            if (!$clientID) {
                $this->authenticate();
            }

            if ($clientID && $clientID != self::CLIENT_ID) {
                // Client id is invalid
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            } else {
                $balance = $this->getBalance( $this->pdoConnection, $phoneNumber);
            }

            $url = 'https://shop.bet9ja.com/Sport/Default.aspx';
            $data = array('h$w$PC$ctl05$txtCodiceCoupon' => $betslip,
                    'h$w$SM' => 'h$w$PC$ctl05$upCheckCoupon|h$w$PC$ctl05$lnkCheckCoupon',
                    '__VIEWSTATEGENERATOR' => '15C4A0A3',
                    '__EVENTTARGET' => 'h$w$PC$ctl05$lnkCheckCoupon',
                    '__ASYNCPOST' => true,
            );

            // use key 'http' even if you send the request to https://...
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                )
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            $r = explode('showPopupCouponCheck', $result)[2];

            $sn = explode(';', $r);
            $data = explode(',', $sn[0]);

            $IDCoupon = $this->getNum($data[0]);
            $IDBookmaker = $this->getNum($data[2]);
            $IDUtente = $this->getNum($data[3]);

            if (!$IDCoupon || !$IDBookmaker || !$IDUtente) {
                $this->jsonResponse(array('success' => false, 'message' => 'Bet slip is invalid', 'code' => Controller::HTTP_SERVER_ERROR_CODE));
            }

            $html = file_get_html('https://shop.bet9ja.com/Sport/CouponCheckDetailsPopup.aspx?IDCoupon=' . $IDCoupon . '&IDBookmaker=' . $IDBookmaker . '&IDUtente=' . $IDUtente);
            $leagues1 = [];
            $fixtures1 = [];
            $outcomes1 = [];
            $outcomesOv1 = [];
            $odds1 = [];
            $dates1 = [];
            $results1 = [];

            $leagues2 = [];
            $fixtures2 = [];
            $outcomes2 = [];
            $outcomesOv2 = [];
            $odds2 = [];
            $dates2 = [];
            $results2 = [];

            $leagues = [];
            $fixtures = [];
            $outcomes = [];
            $outcomesOv = [];
            $odds = [];
            $dates = [];
            $results = [];

            foreach($html->find('.dgAItemStyle') as $elem) {
                $index = 0;
                foreach($elem->find('td') as $td) {
                    if ($index == 0) {
                        $event = explode('-', $td->innertext);
                        array_push($leagues2, trim($event[0]));
                        array_push($fixtures2, trim($event[1] . '-' . $event[2]));
                    }
            
                    if ($index == 1) {
                        array_push($dates2, trim($td->innertext));
                    }
            
                    if ($index == 2) {
                        array_push($outcomes2, trim($td->innertext));
                    }
            
                    if ($index == 3) {
                        array_push($outcomesOv2, trim($this->getNum($td->innertext)));
                    }
            
                    if ($index == 4) {
                        array_push($odds2, trim($td->innertext));
                    }
            
                    if ($index == 6) {
                        $resultHtml = str_get_html($td->innertext);
                        $t = '';
                        foreach($resultHtml->find('span') as $e) {
                            $t = $e->innertext;
                            if ($t) {
                                break;
                            }
                        }
                       array_push($results2, $t);
                    }
            
                    $index+=1;
                }
            }
            
            foreach($html->find('.dgItemStyle') as $elem) {
                $index = 0;
                foreach($elem->find('td') as $td) {
                    if ($index == 0) {
                        $event = explode('-', $td->innertext);
                        array_push($leagues1, trim($event[0]));
                        array_push($fixtures1, trim($event[1] . '-' . $event[2]));
                    }
            
                    if ($index == 1) {
                        array_push($dates1, trim($td->innertext));
                    }
            
                    if ($index == 2) {
                        array_push($outcomes1, trim($td->innertext));
                    }
            
                    if ($index == 3) {
                        array_push($outcomesOv1, trim($this->getNum($td->innertext)));
                    }
            
                    if ($index == 4) {
                        array_push($odds1, trim($td->innertext));
                    }
            
                    if ($index == 6) {
                        $resultHtml = str_get_html($td->innertext);
                        $t = null;
                        foreach($resultHtml->find('span') as $e) {
                            $t = $e->innertext;
                            if ($t) {
                                break;
                            }
                        }
                       array_push($results1, $t);
                    }
                    $index+=1;
                }
            }
            
            $size = sizeof($leagues1);
            
            for ($i = 0; $i < $size; $i++) { 
                   array_push($leagues, $leagues1[$i]);
                   array_push($dates, $dates1[$i]);
                   array_push($odds, $odds1[$i]);
                   array_push($outcomes, $outcomes1[$i]);
                   array_push($outcomesOv, $outcomesOv1[$i]);

                   array_push($results, $results1[$i]);
                   array_push($fixtures, $fixtures1[$i]);
                   if ($i == $size - 1 && sizeof($leagues1) > sizeof($leagues2))
                        continue;
                    
                    array_push($leagues, $leagues2[$i]);
                    array_push($dates, $dates2[$i]);
                    array_push($odds, $odds2[$i]);
                    array_push($outcomes, $outcomes2[$i]);
                    array_push($outcomesOv, $outcomesOv2[$i]);
                    array_push($results, $results2[$i]);
                    array_push($fixtures, $fixtures2[$i]);
            }
            
            for ($i = 0; $i < sizeof($outcomes); $i++) {
                if ((int)$outcomesOv[$i] > 0) {
                    $outcomes[$i] = $outcomes[$i] . ' ' . $outcomesOv[$i];
                }
            }

            $data = array(
                'leagues' => $leagues,
                'fixtures' => $fixtures,
                'dates' => $dates,
                'outcomes' => $outcomes,
                'odds' => $odds,
                'results' => $results,
                'balance' => $balance,
            );

            if ($clientID) {
                $data = $this->retrieveNeededData((object)$data, $slipPos);
            }

            $this->jsonResponse(array('success' => true, 'data' => $data, 'code' => Controller::HTTP_OKAY_CODE));
        }

        public function retrieveNeededData($data, $pos) {
            $envObj = json_decode(file_get_contents(__DIR__ .'/../.envJson'));

            $livescores = array();

            $leagues = array();
            $fixtures = array();
            $results = array();
            $events = array();
            $outcomes = array();

            $isFetch = false;

            if (!$pos) {
                $pos = [];
                $counter = 1;
                for($i = 1; $i <= sizeof($data->leagues); $i++) {
                    array_push($pos, $i);
                }
            }

            foreach($pos as $p) {
                if (!is_numeric($p))
                    continue;
                
                $index = (int)$p - 1;

                if ($index >= sizeof($data->leagues))
                    continue;
                
                $result = $data->results[$index];
                $time = null;

                if (!$result) {

                    $date = $this->convertBet9jaDate($data->dates[$index]);
                    if ($date > gmdate("Y-m-d\ H:i:s")) {
                        $time = 'NS';  // abbreviation for not started
                    } else if (!$isFetch) {
                        $url = $envObj->API_URL . '/soccer24/live-score';
                        $liveData = json_decode(file_get_contents($url));
                        $livescores = $liveData->data;

                        $isFetch = true;
                        $fixture = explode(' - ', $data->fixtures[$index]);
                        $liveScore = $this->getResult($fixture, $livescores);
                        $result = $liveScore ? $liveScore->score : 'NF';
                        $time = $liveScore->time ? $liveScore->time : 'NF';
                    } else if ($livescores) {
                        $fixture = explode(' - ', $data->fixtures[$index]);
                        $liveScore = $this->getResult($fixture, $livescores);
                        $result = $liveScore ? $liveScore->score : 'NF';
                        $time = $liveScore->time ? $liveScore->time : 'NF';
                    }
                } else {
                    $time = 'FT';
                }

                if (!$isFetch && !$livescores) {
                    // something  is not right here
                }

                array_push($fixtures, $data->fixtures[$index]);
                array_push($leagues, $data->leagues[$index]);
                array_push($outcomes, $data->outcomes[$index]);
                array_push($results, $result);
                array_push($events, $time);
            }

            return array(
                'leagues' => $leagues,
                'events' => $events,
                'results' => $results,
                'fixtures' => $fixtures,
                'outcomes' => $outcomes,
            );
        }

        public function convertBet9jaDate($date) {
            $dateTime = explode(' ', $date);
            $dateArr = explode('/', $dateTime[0]);

            $timeArr = explode(':', $dateTime[1]);
            $timeStr = (string)((int)$timeArr[0] - 1) . ':' . $timeArr[1] . ':' . $timeArr[2];

            return $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0] . ' ' . $timeStr;
        }

        public function getResult($fixture, $livescores) {
            foreach($livescores as $livescore) {
                if ($this->isMatch($livescore->home_name, $livescore->away_name, $fixture)) {
                    return $livescore;
                }
            }

            return null;
        }

        public function getMinutesDiffFromNow($dateStr){
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
        public function checkTeamMatch($fromApi, $fromBooking) {
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
    
        public function isMatch($homeName, $awayName, $homeAwayArr) {
            if ($homeName == $homeAwayArr[0] && $awayName == $homeAwayArr[1]) {
                true;
            }
    
            $isSubStringForHome = $this->checkTeamMatch($homeName, $homeAwayArr[0]);
            $isSubStringForAway = $this->checkTeamMatch($awayName, $homeAwayArr[1]); // || strpos($homeAwayArr[1], $awayName);
            
            if ($isSubStringForHome && $isSubStringForAway)
                return true;
            
            return false;
        }

        public function getCashout() {
            $betslip = $this->request->query['betslip'] ? $this->request->query['betslip'] : null;
            $phoneNumber = $this->request->query['phone_number'] ?  $this->request->query['phone_number'] : null;
            $this->pdoConnection->open();

            $balance = $this->getBalance($this->pdoConnection, $phoneNumber);
            $url = 'https://shop.bet9ja.com/Sport/Default.aspx';
            $data = array('h$w$PC$ctl05$txtCodiceCoupon' => $betslip,
                    'h$w$SM' => 'h$w$PC$ctl05$upCheckCoupon|h$w$PC$ctl05$lnkCheckCoupon',
                    '__VIEWSTATEGENERATOR' => '15C4A0A3',
                    '__EVENTTARGET' => 'h$w$PC$ctl05$lnkCheckCoupon',
                    '__ASYNCPOST' => true,
                );

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                )
            );

            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            $r = explode('showPopupCouponCheck', $result)[2];

            $sn = explode(';', $r);
            $data = explode(',', $sn[0]);

            $IDCoupon = $this->getNum($data[0]);
            $IDBookmaker = $this->getNum($data[2]);
            $IDUtente = $this->getNum($data[3]);
            $urlCashout = 'https://shop.bet9ja.com/Controls/CouponWS.asmx/Cashout_CheckOut';

            $dataCashout = array(
                'IDBookmaker' => $IDBookmaker,
                'IDCoupon' => $IDCoupon,
                'IDUtente' => 0,
                'IDUtenteCoupon' => $IDUtente,
            );
            $headers  = [
                'Content-Type: application/json; charset=UTF-8'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlCashout);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataCashout));           
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result     = curl_exec ($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $data = json_decode($result);

            $this->jsonResponse(array('success' => $data->d->isValid, 'data' => array('amount' => $data->d->amount), 'code' => Controller::HTTP_OKAY_CODE));
        }

        public function updateBalance() {
            $phoneNumber = $this->request->query['phone_number'] ?  $this->request->query['phone_number'] : null;
            $usedCredits = $this->request->query['used_credits'] ?  $this->request->query['credits'] : null;
            $this->pdoConnection->open();
            $balance = $this->getBalance($this->pdoConnection, $phoneNumber);

            if ($balance < $usedCredits) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST, 'message' => 'Credits not enoug!'));
            }

            $newBalance = (int)$balance - (int)$usedCredits;

            if (SmsModel::updateBalance($this->pdoConnection, $phoneNumber, $newBalance)) {
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE));
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error could not update balance'));
        }
    }
?>
