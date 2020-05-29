<?php

/**
 * This class handles both the signup and 
 * the signin or login action. The difference between the two 
 * is by the parameter `type`. if login the parmeter will ne `login`
 * other wise `signup
 */
class LoginController extends Controller {

    public function  __construct($request) {
        parent::__construct($request);
    }

    public function validate() {
        if ($this->request->method == 'GET') { 
            return true;
        }

        // var_dump(!empty($this->request->postData['password'])); exit;
        if ($this->request->postData['type'] == 'login') {
            if (!empty($this->request->postData['password']) && 
            !empty($this->request->postData['email']) && filter_var($this->request->postData['email'], FILTER_VALIDATE_EMAIL)) {
                var_dump($this->request); exit;
                return true;
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid email or password'));

        } else if ($this->request->postData['type'] == 'signup') {
            $firstNameValidity = Validation::isValidName($this->request->postData['firstName']);
            $lastNameValidity = Validation::isValidName($this->request->postData['lastName']);

            if (empty(trim($this->request->postData['email']))) {
                $this->error['email'] = 'Email address can not be empty';
            } else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error['email'] = 'Invalid email address';
            }

            if (!$firstNameValidity['isValid']) {
                $this->error['firstName'] = $firstNameValidity['message'];
            }

            if (!$lastNameValidity['isValid']) {
                $this->error['lastName'] = $lastNameValidity['message'];
            }

            if ($this->request->postData['sex'] != 'Male' || $this->request->postData['sex'] != 'Female') {
                $this->error['sex'] = 'Invalid sex';
            }

            if (sizeof($this->error) == 0) return true;

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => $this->error));
        } else {
            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'messages' => "Unexpected error occured"));
        }

    }

    public function perform() {
        if ($this->request->method == 'GET') {
            $this->data['template'] = 'Login.php';
            $this->data['title'] = 'Login | Signup';
            $this->responseType = 'html';
        } else {
            $this->pdoConnection->open();

            if ($this->request->postData['type'] == 'login') {
                $user = UserModel::getUser($this->request->postData['email'], $this->request->postData['password']);

                if ($user == false)
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_COD, 'message' => 'Server error'));

                if (!$user)
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_UNAUTHORIZED_CODE,  'message' => 'Invalid username or password'));
                
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'User successfuly logs in'));
            }

            $country = $this->request->postData['country'];
            $state = $this->request->postData['state'];

            if (!$this->request->postData['country'])
                $country = null;

            if (!$this->request->postData['state'])
                $state = null;

            $result = UserModel::createUser($this->pdoConnection, $this->request->postData['name'], $this->request->postData['email'], $this->request->postData['password'], 
                $this->request->postData['sex'], $country, $state);

            if ($result)
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'User successfuly signs up'));

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_COD, 'message' => 'Server error'));

        }
    }
}

?>