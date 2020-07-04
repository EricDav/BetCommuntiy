<?php 
    class PredictionController extends Controller {
        public function __construct($request) {
            parent::__construct($request);
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

        }

        public function validateDelete() {
            // checks if prediction id is sent to the sever or post request
            if (!isset($this->request->postData['prediction_id'])) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            }

            // checks if the prediction id is in its correct format
            if (!is_numeric($this->request->postData['prediction_id'])) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid prediction id'));
            }
    
            // Retrieve the prediction id 
            $prediction = PredictionModel::getPredictionById($this->pdoConnection, $this->request->postData['prediction_id']);

            // check for server error 
            if ($prediction === false) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            }

            // check if prediction is found
            if (!$prediction) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_NOT_FOUND, 'messages' => 'Prediction not found'));
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
                echo 'I got here'; exit;
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
            $this->data['title'] = 'BetCommunity | Prediction';
            $this->data['outcomes'] = BetCommunity::OUTCOMES;
            $this->responseType = 'html';

        }
    }


?>