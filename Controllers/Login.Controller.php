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
        $this->pdoConnection->open();
    }

    public function validate() {
        if ($this->request->method == 'GET') { 
            return true;
        }

        if ($this->request->postData['type'] == 'login') {
            if (!empty($this->request->postData['password']) && 
            !empty($this->request->postData['email']) && filter_var($this->request->postData['email'], FILTER_VALIDATE_EMAIL)) {
                return true;
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid email or password'));

        } else if ($this->request->postData['type'] == 'signup') {
           return $this->handleSignupValidation();
        } else {
            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'messages' => "Unexpected error occured"));
        }
    }

    /**
     * Helps to handle sign up validations
     */
    public function handleSignupValidation() {

        // retrieve names from user inputs
        $firstNameValidity = Validation::isValidName($this->request->postData['firstName']);
        $lastNameValidity = Validation::isValidName($this->request->postData['lastName']);

        if (empty(trim($this->request->postData['password']))) {
            $this->error['password'] = 'Password can not be empty';
        }
        
        if (empty(trim($this->request->postData['email']))) {
            $this->error['email'] = 'Email address can not be empty';
        } else if (!filter_var($this->request->postData['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = 'Invalid email address';
        }

        if (!$firstNameValidity['isValid']) {
            $this->error['firstName'] = $firstNameValidity['message'];
        }

        if (!$lastNameValidity['isValid']) {
            $this->error['lastName'] = $lastNameValidity['message'];
        }

        if ($this->request->postData['sex'] != 'Male' && $this->request->postData['sex'] != 'Female') {
            $this->error['sex'] = 'Invalid sex';
        }

        if (sizeof($this->error) == 0) {
            $user = UserModel::getUserByEmail($this->pdoConnection, $this->request->postData['email']);
            if ($user == 'Server error') // return server error response if there is an exception thrown when retrieving user data
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            
            if ($user)
                $this->error['email'] = 'Email address already exist';
        }
        
        if (sizeof($this->error) == 0) return true;

        $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => $this->error));
    }

    public function perform() {
        if ($this->request->method == 'GET') {
            $this->data['template'] = 'Login.php';
            $this->data['title'] = 'Login | Signup';
            $this->responseType = 'html';
        } else {
            if ($this->request->postData['type'] == 'login') {
                 /**
                 * This handles login actions
                 */
                $user = UserModel::getUserByEmail($this->pdoConnection, $this->request->postData['email']);

                if ($user == 'Server error')
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));

                // If user with email doesn't exist or password hash doesn't match
                // respond with a failure
                if (!$user || !password_verify($this->request->postData['password'], $user['password']))
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_UNAUTHORIZED_CODE,  'message' => 'Invalid username or password'));
                
                $this->setUserSession($user['name'], $user['email'], $user['special_id'], $user['id'], $user['role'], $user['image_path']);
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'User successfuly logs in'));
            }

            /**
             * This handles sign up actions
             */
            $country = $this->request->postData['country'];
            $city = $this->request->postData['city'];
            $sex = $this->request->postData['sex'] == 'Male' ? 'M' : 'F';
            $name = $this->request->postData['firstName'] . ' ' . $this->request->postData['lastName'];
            $passwordHash = password_hash($this->request->postData['password'], PASSWORD_DEFAULT);

            if (!$this->request->postData['country'])
                $country = null;

            if (!$this->request->postData['city'])
                $city = null;

            $result = UserModel::createUser($this->pdoConnection, $name, $this->request->postData['email'], $passwordHash, 
                $sex, $country, $city);
            
            if ($result) {
                $imagePath = $sex == 'M' ? UserModel::DEFAULT_IMAGE_PATH_MALE : UserModel::DEFAULT_IMAGE_PATH_FEMALE;
                $this->setUserSession($name, $this->request->postData['email'], $result['specialId'], $result['id'], 1, $imagePath);
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'User successfuly signs up'));
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
        }
    }

    public function setUserSession($name, $email, $specialId, $id, $role, $imagePath) {
        $userInfo = array(
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'specialId' => $specialId,
            'role' => $role,
            'imagePath' => $imagePath
        );

        $_SESSION['userInfo'] = $userInfo;
    }
}

?>
