<?php
abstract class Controller {
    public $data;
    private $template;
    public $request;
    public $responseType;
    public $pdoConnection;
    public $error;

    const HTTP_BAD_REQUEST_CODE = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_SERVER_ERROR_CODE = 500;
    const HTTP_UNAUTHORIZED_CODE = 401;
    const HTTP_OKAY_CODE = 200;
    const DEFAULT_LIMIT = 20;
    const DEFAULT_OFFSET = 1;


    public function __construct($request) {
        $this->data = array();
        $this->request = $request;
        $this->responseType = null;
        $this->pdoConnection = new PDOConnection();
        $this->error = array();
        $this->currentSideBarFilter = null;
    }

    public function authenticate($id=null, $token=null) {
        // check if user is logged in
        if (!isset($this->request->session['userInfo'])) {
            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_UNAUTHORIZED_CODE, 'message' => 'User not logged in'));
        }

        if (!$token)
            $token = isset($this->request->postData['token']) ? $this->request->postData['token'] : $this->request->query['token'];
        
        if (!$id)
            $id = isset($this->request->postData['id']) ? $this->request->postData['id'] : $this->request->query['id'];

        $userId = $this->request->session['userInfo']['specialId'] . $this->request->session['userInfo']['id'];
        // Verify token and id coming from client
        if ($token != $this->request->session['token'] ||  $id != $userId) {
            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Something is not right'));
        }
    }


    public function setData($data) {
        $this->data = $data;
    }

    public function setToken() {
        if ($this->isLogin()) {
            // var_dump('I got here'); exit;
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            // echo $token; exit;
            $_SESSION['token'] = $token;
            $this->data['token'] = $token;
        }
    }
    /**
     * Checks if current user is logged in
     */
    public function isLogin() {
        if (isset($_SESSION['userInfo']))
            return true;
        return false;
    }

    public function getPredictionStatus($prediction) {
        if ($prediction['won']) {
            return ((int)$prediction['won'] == 0 ? 'Lost <i style="color: red;" class="fa fa-close"></i>' : 'Won <i style="color: green;" class="fa fa-check"></i>');
        }

        return ($prediction['start_date'] > gmdate("Y-m-d\ H:i:s") ? '  Not started' : '  Running');
    }

    public function setResponseType($responseType) {
        $this->responseType = $responseType;
    }

    public function getData() {
        return $this->data;
    }

    public function jsonResponse($response) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function setDateCreatedUTC($predictions) {
        $dates = array();
        foreach($predictions as $prediction) {
            array_push($dates, array('date_created' => $prediction['created_at'], 'id' => $prediction['id']));
        }
        $this->data['dates'] = $dates;
    }
    
    /**
    * @param user_id the id of the user we want to know 
    * if the current user is following.
    * 
    * Checks if a user is among the users the current 
    * user is following
    */
    public function isFollowing($userId) {
        if (!$this->isLogin())
            false;
            
        $data = $this->data;
        if (sizeof($data['followers']) == 0) 
            return false;
        foreach($data['followers']  as $follower) {
            if ($userId == $follower['user_id']) 
                return true;
        }

        return false;
    }

    public function setUserSession($name, $email, $specialId, $id, $role, $imagePath, $phoneNumber) {
        $userInfo = array(
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'specialId' => $specialId,
            'role' => $role,
            'imagePath' => $imagePath,
            'phoneNumber' => $phoneNumber,
        );

        $_SESSION['userInfo'] = $userInfo;
    }

    public static function isSessionSet(){
        if(count($_SESSION) > 0 && array_key_exists('userInfo', $_SESSION)){
            return true;
        }

        return false;
    }
  
    /**
    * Checks if date and time is valid
    * @param {String} date
    * @param {String} time
    * 
    * take date and time as parameters in this form Y/m/d H:s
    */
    public function isValidateDateTime($date, $time) {
        $dateArr = explode('-', $date);
        $timeArr = explode(':', $time);
        $time = $timeArr[0] . ':' . $timeArr[1]; // Enforce time to be in the format HH:mm
        
        $isValiddate = checkdate($dateArr[1], $dateArr[2], $dateArr[0]);

        $isValidTime = preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time);  // Checks if a time is valid in this format HH:mm

        if ($isValiddate && $isValidTime) 
            return true;
        return false;
    }

    /**
    * This functions set the user_i, prediction_id of 
    * the prediction. It is used at the client side to make 
    * onclick events for following and liking users. And also
    * used for displaying prediciton data
    * 
    * Note: This function should come after the when we 
    * have all the followers, or we have called the getFollowers method.
    */
    public function setPredictionInfo($predictions) {
            $predictionInfo = array();

            foreach($predictions as $prediction):
                $firstName = explode(' ', $prediction['name'])[0];
                array_push($predictionInfo, array('user_id' => $prediction['user_id'], 
                    'prediction_id' => $prediction['id'],
                    'isFollowing_author' => $this->isFollowing($prediction['user_id']),
                    'first_name' => $firstName,
                    'prediction' => $prediction['prediction'],
                    'prediction_type' => $prediction['type']
                ));
            endforeach;

            $this->data['predictionInfo'] = $predictionInfo;
    }

    public function getMinutesDiff($dateStr, $otherDateStr){
        $startDate = new DateTime($dateStr);
        $sinceStart = $startDate->diff(new DateTime($otherDateStr));

        $minutes = $sinceStart->days * 24 * 60;
        $minutes += $sinceStart->h * 60;
        $minutes += $sinceStart->i;
        
        return $minutes;
    }

    /**
    * It helps to highlight the current filter
    */
    public function formatFilterText($text) {
        if ($this->currentSideBarFilter == $text) {
            return '<b style="color:black; font-size: 15px;">' . $text . '</b>';
        }

        return $text;
    }

    public function set404Page() {
        $this->data['template'] = '404.php';
        $this->data['title'] = '404 | Not found';
        $this->responseType = 'html';
    }

    abstract function validate();

    abstract function perform();
}
?>
