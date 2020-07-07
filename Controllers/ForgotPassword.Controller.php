<?php
class ForgotPasswordController extends Controller{

    private $requestMethod;
    public $pdoConnection;
    public $request;
    public $message;

    public function __construct($request){
        parent::__construct($request);
        $this->requestMethod = $request->method;
        $this->email = $request->postData['email'] ? $request->postData['email'] : null;
        $this->pdoConnection->open();
    }

    public function validate() {
        if ($this->requestMethod == 'GET') {
            return true;
        } 

        if($this->requestMethod == 'POST') {
            if (!$this->email || !Validation::isEmailValid($this->email)) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'message' => 'Invalid email'));
            }
            return true;
        }
    }

    private function doesPasswordMatch($password, $passwordDuplicate){
        if($password === $passwordDuplicate) {
            return true;
        } else {
            return false;
        }
    }

    public function perform() {
        if ($this->requestMethod == 'GET') {
            $this->data['template'] = 'ForgotPassword.php';
            $this->data['title'] = 'BetCommunity | Forgot password';
            $this->data['message'] = '';
            return;
        }

        $user = UserModel::getUserByEmail($this->pdoConnection, $this->email);

        if ($user === false) {
            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'message' => 'Email not found'));
        }

        if ($user === 'Server error') {
            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
        }

        // For request code
        if (isset($this->request->postData['code'])) {
            if ($user['code_token'] == null) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'An error occurred please refresh the page'));
            }

            if ($user['token_count'] >= 3) {
                // Todo block the user
            }
            
            if ($user['code_token'] != $this->request->postData['code']) {
               //  HTTP_UNAUTHORIZED_CODE 
               $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_UNAUTHORIZED_CODE, 'message' => 'Code not correct'));
               // Todo => update count by one
            }

            if ($user['code_token'] == $this->request->postData['code']) {
                $_SESSION['temp_user'] = array(
                    'name' => $user['name'],
                    'id' => $user['id'],
                    'code' => $user['code_token']
                );
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'Code validated'));
             }

             return;
        }

        // For sending code to user email
        $randCode = mt_rand(100000,999999);

        $count = $user['code_count'] ? (int)$user['code_count'] + 1 : 0;
        if (UserModel::updateUserResetCode($this->pdoConnection, $this->email, $randCode, $count)) {
            $mailObj = new SendMail($user['email'], 'Reset Passwoord', $this->getMessage($user['name'], $randCode));
            if ($mailObj->send())
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'Code sent successfully'));
            
            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Code not sent try again'));   
        }
    }

    public function getMessage($name, $code) {
        $htmlMessage = '<div style="margin-left: 10px; margin-top: 20px; line-height: 1.5; text-align: left"><b>Hi ' . $name . '</b>, ' .  '<span font-weight: 500;> Your betcommunity password reset code is <b>' . $code . '</span>' . '</div>';
        $htmlMessage .='<div style="margin-left: 10px;line-height: 1.5; text-align: left">If you did not ask to reset your password, please ignore this email and nothing will change.</div>';

        return $htmlMessage;
    }

    private function setSession($info){
        $_SESSION = $info;
    }

    public function resetPassword() {
        // header('Location: /forgot-password');
        if ($this->request->method == 'GET') {
            if (isset($_SESSION['temp_user'])) {
                $this->data['code'] = $_SESSION['temp_user']['code'];
                $this->data['template'] = 'ForgotPassword.php';
                $this->data['title'] = 'BetCommunity | Reset Password';
            } else {
                header('Location: /forgot-password');
                exit;
            }
        } 

        if ($this->request->method == 'POST') {
            if (!isset($_SESSION['temp_user'])) {
                // echo 'Here2!'; exit;
                header('Location: /forgot-password');
                exit;
            }

            $code = $this->request->postData['code'] ? $this->request->postData['code'] : null;
            $password = $this->request->postData['code'] ? $this->request->postData['password'] : null;

            if (!$code || $code != $_SESSION['temp_user']['code']) {
                echo 'Here1!'; exit;
                header('Location: /forgot-password');
                exit;
            }

            if (!$password) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'message' => 'Password is required'));
            }

            if (UserModel::updateUserPassword($this->pdoConnection,password_hash($password, PASSWORD_DEFAULT), $_SESSION['temp_user']['id'], $code)) {
                unset($_SESSION['temp_user']);
                $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'message' => 'Password updated successfully'));
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Could not update password'));   

        }
    }
 }
?>
