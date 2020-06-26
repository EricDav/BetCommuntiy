<?php 
    /**
     * @route /api/web/update-profile
     * 
     * It handles the update profile request with different 
     * actions. 
     * 
    */
    class UpdateUserProfileController extends Controller {
        const ALLOWED_ACTIONS = ['update_password', 'update_profile', 'update_settings'];
        const DEFAULT_IMAGE_PATHS = [UserModel::DEFAULT_IMAGE_PATH_MALE, UserModel::DEFAULT_IMAGE_PATH_FEMALE];

        public function __construct($request) {
            parent::__construct($request);
            $this->type = $request->postData['action'];
            $this->firstName =  isset($request->postData['firstName']) ? $request->postData['firstName'] : null;
            $this->lastName =  isset($request->postData['lastName']) ? $request->postData['lastName'] : null;
            $this->sex =  isset($request->postData['sex']) ? $request->postData['sex'] : null;
            $this->phoneNumber =  isset($request->postData['phoneNumber']) ? $request->postData['phoneNumber'] : null;
            $this->city =  isset($request->postData['city']) ? $request->postData['city'] : null;
            $this->country =  isset($request->postData['country']) ? $request->postData['country'] : null;
            $this->email = isset($request->postData['email']) ? $request->postData['email'] : null;
            $this->password =  isset($request->postData['password']) ? $request->postData['password'] : null;
            $this->oldPassword =  isset($request->postData['oldPassword']) ? $request->postData['oldPassword'] : null;
            $this->settings = isset($request->postData['settings']) ? $request->postData['settings'] : null;
            $this->method = $request->method;
            $this->ext = null;
            $this->pdoConnection->open();
        }

        public function validate() {
            if (isset($_COOKIE['id']) && isset($_COOKIE['token'])) {
                $this->authenticate($_COOKIE['id'], $_COOKIE['token']);
            
                $filename = $_FILES['file']['name'];
                $location = "Public/images/users/". $filename;
                $uploadOk = true;
                $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

                // var_dump($filename); exit;
                $valid_extensions = array("jpg","jpeg","png");
                if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
                    $uploadOk = false;
                }

                if (!$uploadOk) {
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid image type or extension'));
                }

                $this->ext = $imageFileType;
                return true;
            }

            $this->authenticate();
            if (!$this->type || !in_array($this->type, self::ALLOWED_ACTIONS)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Action type not specified or invalid'));
            }

            if ($this->type == 'update_profile') {
                // calls the signup validation as it has the same parameters
                return $this->handleProfileUpdateValidation();
            }

            if ($this->type == 'update_password') {
                return $this->handleUpdatePasswordValidation();
            }

            
        }

        public function perform() {
            if (isset($_COOKIE['id']) && isset($_COOKIE['token'])) {
                // var_dump($_FILES['file']['name']); exit;
                $newFilename = sha1($this->request->session['userInfo']['email'] . $_FILES['file']['name']) . '.' . $this->ext;

                if (UserModel::updateUserProfilePhotoUrl($this->pdoConnection, $newFilename, $this->request->session['userInfo']['id'])) {
                    if (!in_array($this->request->session['userInfo']['imagePath'], self::DEFAULT_IMAGE_PATHS)) {
                        unlink( __DIR__ . '/..' . '/Public/images/users/' . $this->request->session['userInfo']['imagePath']);
                    }

                    if (move_uploaded_file($_FILES['file']['tmp_name'],  __DIR__ . '/..' . '/Public/images/users/' . $newFilename)); {
                        $_SESSION['userInfo']['imagePath'] = $newFilename;
                        $this->jsonResponse(array('success' => true, 'url' => '/bet_community/Public/images/users/' . $newFilename,  'code' => Controller::HTTP_OKAY_CODE, 'message' => 'User profile picture updated successfully'));
                    }

                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
                } else {
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
                }
            }

            if ($this->type == 'update_profile') {
                $name = $this->firstName . ' ' . $this->lastName;
                $sex = $this->sex == 'Male' ? 'M' : 'F';
                $result = UserModel::updateUserDetails($this->pdoConnection, $name, $this->email, $sex,
                    $this->country, $this->city, $this->phoneNumber, $this->request->session['userInfo']['id']);
                
                if (!$result)
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));

                $this->updateSessionDetails();
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'User details updated successfully'));
            }

            if ($this->type == 'update_password') {
                $result = UserModel::updateUserPassword($this->pdoConnection, password_hash($this->password, PASSWORD_DEFAULT), $this->request->session['userInfo']['id']);
                if (!$result)
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));

                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'User password updated successfully'));
            }
        }

        public function handleProfileUpdateValidation() {
            // retrieve names from user inputs
            $firstNameValidity = Validation::isValidName($this->firstName);
            $lastNameValidity = Validation::isValidName($this->lastName);
            $isValidCity = Validation::isValidResident($this->city);
            // var_dump($this->city); exit;
            if (!$firstNameValidity['isValid']) {
                $this->error['firstName'] = $firstNameValidity['message'];
            }

            if (!$lastNameValidity['isValid']) {
                $this->error['lastName'] = $lastNameValidity['message'];
            }

            if (!$isValidCity ['isValid']) {
                $this->error['city'] = $isValidCity['message'];
            }

            if (empty(trim($this->email))) {
                $this->error['email'] = 'Email address can not be empty';
            } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->error['email'] = 'Invalid email address';
            }

            if ($this->sex != 'Male' && $this->sex != 'Female') {
                $this->error['sex'] = 'Invalid sex';
            }

            if ($this->phoneNumber && is_numeric(!$this->phoneNumber)) {
                $this->error['phoneNumber'] = 'Invalid phone number';
            }

            if (sizeof($this->error) == 0 && $this->isEmailUpdated() && $this->isPhoneNumberUpdated()) {
                $user = UserModel::getUserByEmailPhone($this->pdoConnection, $this->email, $this->phoneNumber);
                if ($user == 'Server error') // return server error response if there is an exception thrown when retrieving user data
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
                
                if ($user) {
                    if ($user['email'] == $this->email) 
                        $this->error['email'] = 'Email address already exist';
                    if ($user['phone_number'] == $this->phoneNumber) 
                        $this->error['phoneNumber'] = 'Phone number already exist';
                }
            } else if (sizeof($this->error) == 0 && $this->isEmailUpdated()) {
                $user = UserModel::getUserByEmail($this->pdoConnection, $this->email);
                if ($user == 'Server error') // return server error response if there is an exception thrown when retrieving user data
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
                
                if ($user)
                    $this->error['email'] = 'Email address already exist';

            } else if (sizeof($this->error) == 0 && $this->isPhoneNumberUpdated()) {
                $user = UserModel::getUserByPhoneNumber($this->pdoConnection, $this->phoneNumber);
                if ($user == 'Server error') // return server error response if there is an exception thrown when retrieving user data
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
                
                if ($user) 
                    $this->error['phoneNumber'] = 'Phone number already exist';
            }



            if (sizeof($this->error) == 0) return true;

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => $this->error));
        }

        public function isEmailUpdated() {
            return $this->email != $this->request->session['userInfo']['email'];
        }

        public function isPhoneNumberUpdated() {
            // var_dump( $this->request->session['userInfo']);
            return $this->phoneNumber != $this->request->session['userInfo']['phoneNumber'];
        }

        public function handleUpdatePasswordValidation() {
            if (!$this->password || !$this->oldPassword) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Both password and old password are required'));
            }
            $user = UserModel::getUserById($this->pdoConnection, $this->request->session['userInfo']['id']);

            if (!password_verify($this->oldPassword, $user[0]['password'])) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Old password is not correct'));
            }

            return true;
        }

        public function updateSessionDetails() {
            $specialId = $this->request->session['userInfo']['specialId'];
            $role = $this->request->session['userInfo']['role'];
            $id = $this->request->session['userInfo']['id'];
            $imagePath = $this->request->session['userInfo']['imagePath'];
            $this->setUserSession($this->firstName . ' ' . $this->lastName, $this->email, $specialId, $id, $role, $imagePath, $this->phoneNumber);
        }
    }



?>