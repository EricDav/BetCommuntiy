<?php 
    class CreatePredictionController extends Controller {
        public function __construct($request) {
            parent::__construct($request);
            $this->startDateTime = $this->request->postData['start_date_time'];
            $this->endDateTime = $this->request->postData['end_date_time'];
            $this->prediction = $this->request->postData['prediction'];
            $this->totalOdds =  $this->request->postData['total_odds'];
            $this->token = $this->request->postData['token'];
            $this->id = $this->request->postData['id'];
        }

        public function validate() {
            // check if user is logged in
            if (!isset($this->request->session['userInfo'])) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_UNAUTHORIZED_CODE, 'message' => 'User not logged in'));
            }

            $userId = $this->request->session[userInfo]['specialId'] . $this->request->session[userInfo]['id'];

            // Verify token and id coming from client
            if ($this->token != $this->request->session['token'] ||  $this->id != $userId) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Something is not right'));
            }


            // Validate odds
            if (!is_numeric($this->totalOdds)) {
                $this->error['odds'] = 'Invalid odd odds should be in numbers';
            }

            // Validate date and time
            $startDateTimeArr = explode(' ', $this->startDateTime);
            $startDate = $startDateTimeArr[0];
            $startTime = $startDateTimeArr[1];

            $endDateTimeArr = explode(' ', $this->endDateTime);
            $endDate = $endDateTimeArr[0];
            $endTime = $endDateTimeArr[1];

            if ($this->validateDateTime($startDate, $startTime)) {
                $this->error['start_date_time'] = 'Invalid game start date time';
            }

            if ($this->validateDateTime($endDate, $endTime)) {
                $this->error['end_date_time'] = 'Invalid game end date time';
            }

            // var_dump($startDate . ' ' . $startTime);
            // var_dump($endDate . ' ' . $endTime); exit;

            if ($startDate . ' ' . $startTime > $endDate . ' ' . $endTime) {
                $this->error['date_time'] = 'Start game date can not be bigger than end date';
            }

            if (empty(trim($this->prediction))) {
                $this->error['prediction'] = 'Prediction is required';
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
            $approved = $this->request->session['userInfo']['role'] > 1 ? 1 : 0;
            $userId = $this->request->session['userInfo']['id'];
            $result = PredictionModel::createPrediction($this->pdoConnection, $this->startDateTime, $this->endDateTime, $userId, $this->totalOdds, $this->prediction, $approved);

            if ($result)
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'messages' => 'Prediction created successfully'));

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
        }

        /**
         * Checks if date and time is valid
         * 
         * take date and time as parameters in this form Y/m/d H:s
         */
        public function validateDateTime($date, $time) {
            $dateArr = explode('-', $date);
            $isValiddate = checkdate($dateArr[1], $dateArr[3], $dateArr[0]);
            $isValidTime = preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time);

            if ($isValiddate && $isValidTime) 
                return true;
            return false;
        }
    }

?>