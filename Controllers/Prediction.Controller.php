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
    }


?>