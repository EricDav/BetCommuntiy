<?php 
    class NotificationController extends Controller {
        public function __construct($request) {
            parent::__construct($request);
            $this->pdoConnection->open();
        }

        public function validate() {
            $this->authenticate();
            return true;
        }

        public function perform() {
            $notifications = NotificationModel::getPredictions($this->pdoConnection, $this->request->session['userInfo']['id']);

            if ($notifications === false) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            }

            $this->jsonResponse(array('success' => true, 'data' => $notifications, 'code' => Controller::HTTP_OKAY_CODE));
        }

        public function clearSeen() {
            $this->authenticate();

            if (NotificationModel::clearSeen($this->pdoConnection, $this->request->session['userInfo']['id'])) {
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'seen cleared succesfully'));
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
        }
    }


?>