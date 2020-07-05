<?php
class forgotPasswordController extends Controller{

    private $requestMethod;
    public $pdoConnection;
    public $request;
    public $message;

    public function __construct(){
        $this->request = new request();
        $this->requestMethod = $this->request->method;
        parent::__construct($this->request);
        $this->pdoConnection->open();
    }

    public function validate(){
        if($this->requestMethod == 'GET'){
            return true;
        } 
        if($this->requestMethod == 'POST'){
            $request = $_POST['request'];
            if($request == 'send_reset_link'){
                $email = $_POST['email'];
                $check = new Validation();
                if($check::isEmailValid($email)['isValid']){
                    return true;
                } 
                else{
                    $this->jsonResponse(
                        array(
                            'success' => false, 
                            'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                            'messages' => $check::isEmailValid($email)['message']
                        )
                    );
                    return false;
                }
            }else if($request == 'reset_password'){
                $password = $_POST['password'];
                $passwordDuplicate = $_POST['passwordDuplicate'];
                if(empty($password)){
                    $this->jsonResponse(
                        array(
                            'success' => false, 
                            'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                            'messages' => "Password field empty"
                        )
                    );
                    return false;
                    exit;
                }else if(empty($passwordDuplicate)){
                    $this->jsonResponse(
                        array(
                            'success' => false, 
                            'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                            'messages' => "Please retype password"
                        )
                    );
                    return false;
                    exit;
                }else{
                    if($this->doesPasswordMatch($password, $passwordDuplicate)){
                        return true;
                    }else{
                        $this->jsonResponse(
                            array(
                                'success' => false, 
                                'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                                'messages' => "Password do not match"
                            )
                        );
                        return false;
                        exit;
                    }
                }
            }
            
        }
    }





    private function doesPasswordMatch($password, $passwordDuplicate){
        if($password === $passwordDuplicate){
            return true;
        }else{
            return false;
        }
    }
    public function perform(){
        if($this->requestMethod == 'GET'){
            $url = $_SERVER['REQUEST_URI'];
            $length = count($url);
            $url = $url[$length -1] == '/'?substr($url, 0, $length-2):$url;
            $contents = explode('/', $url);
            if(array_key_exists(4, $contents)){
                $this->data['template'] = 'forgotPassword.php';
                $this->data['title'] = 'Bet | Forgot password';
                $this->responseType = 'html';
                $this->message = 'Invalid request';
            }else if(count($contents)==3 ){
                $this->data['template'] = 'forgotPassword.php';
                $this->data['title'] = 'Bet | Forgot password';
                $this->responseType = 'html';
                $this->message = 'Invalid request';
            }else if(count($contents)==2){
                $this->data['template'] = 'forgotPassword.php';
                $this->data['title'] = 'Bet | Forgot password';
                $this->responseType = 'html';
            }else if(array_key_exists(1, $contents) && array_key_exists(2, $contents) && array_key_exists(3, $contents)){
                $identity = htmlspecialchars(filter_var($contents[2],FILTER_SANITIZE_STRING), ENT_QUOTES);;
                $token = htmlspecialchars(filter_var($contents[3],FILTER_SANITIZE_STRING), ENT_QUOTES);
                /**
                 * Check if user request exist on temp-request schema
                 */
                $user = new ForgotPasswordModel();
                $user = $user->doesUserRequestExist($this->pdoConnection, $identity);
                if($user == "" || $user == "Server error"){
                    $this->data['template'] = 'forgotPassword.php';
                    $this->data['title'] = 'Bet | Forgot password';
                    $this->responseType = 'html';
                    $this->message = $user == 'Server error'?$user:'Invalid request';
                }else{
                    /**
                     * Check if token is valid
                     */
                    if(password_verify($token, $user['token'])){
                        /**
                         * Check if token is expired
                         */
                        $expires = strtotime(date('Y-m-d, H:i:s', strtotime($user['expires'])));
                        $now = strtotime(date('Y-m-d, h:i:s', time()));
                      
                        
                        
                        if($now > $expires){
                            $this->data['template'] = 'forgotPassword.php';
                            $this->data['title'] = 'Bet | Forgot password';
                            $this->responseType = 'html';
                            $this->message = 'Token expired';
                        }else{
                            $this->data['template'] = 'forgotPassword.php';
                            $this->data['title'] = 'Bet | Reset Password';
                            $this->responseType = 'html';
                            /**
                             * Store identity in session
                             */
                            $info = [
                                'identity' => $user['identity'],
                                'name' => ucwords($user['name'])
                            ];
                            $this->setSession($info);
                            $this->message = true;
                        }
                    }
                }
            }
        } 
       
        if($this->requestMethod == 'POST'){
            $request = $_POST['request'];
            if($request == 'send_reset_link'){
                /**
                 * Check if user exist
                 */
                $user = UserModel::getUserByEmail($this->pdoConnection, $_POST['email']);
                if($user == 'Server error'){
                    $this->jsonResponse(
                        array(
                            'success' => false, 
                            'code' => Controller::HTTP_SERVER_ERROR_CODE, 
                            'messages' => $user
                        )
                    );       
                }else if($user == ""){
                    $response = $this->jsonResponse(
                        array(
                            'success' => false, 
                            'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                            'messages' => 'Email does not exist'
                        )
                    );
                }else if($user){
                    $name = $user['name'];
                    $email = $user['email'];
                    $special_id = $user['special_id'];
                    $request = $_POST['request'];
                    $dateTime = new DateTime("now");
                    $request_date_time = $dateTime->format('y-m-d h:i:s');
                    $expires = $dateTime->modify("+ 30 minutes");
                    $expires = $dateTime->format('y-m-d h:i:s');
                    $identity = bin2hex(random_bytes(20));
                    $token_to_send = bin2hex(random_bytes(50));
                    $token= password_hash($token_to_send, PASSWORD_DEFAULT);
                    
                    /**
                     * Delete all expired token request
                     */
                    $myrequest = ForgotPasswordModel::getAllTokenRequest($this->pdoConnection);
                    foreach($myrequest as $row){
                        $expires = strtotime(date('Y-m-d, H:i:s', strtotime($row['expires'])));
                        $now = strtotime(date('Y-m-d, h:i:s', time()));
                        if($now > $expires){
                            ForgotPasswordModel::deleteExpiredTokenRequest($this->pdoConnection, $row['id']);
                        }
                    }
                  


                    /**
                     * Does user exist in the temp request schema with a non expired token
                     * update token if yes
                     * else create request
                     */
                    $userRequest = ForgotPasswordModel::getUserRequest($this->pdoConnection, $name, $email, $special_id, $request, $request_date_time);
                    if($userRequest == 'Server error'){
                        $this->jsonResponse(
                            array(
                                'success' => false, 
                                'code' => Controller::HTTP_SERVER_ERROR_CODE, 
                                'messages' => $user
                            )
                        ); 
                    }else if($userRequest == ""){
                        /**
                         * Create user reset password request
                         */
                        if(ForgotPasswordModel::createRequest($this->pdoConnection, $name, $email, $special_id, $request, $request_date_time, $expires, $identity, $token, $token_to_send)){
                            /**
                             * Send a mail at this point befor the
                             */
                            $this->jsonResponse(
                                array(
                                    'success' => true, 
                                    'code' => Controller::HTTP_OKAY_CODE, 
                                    'messages' => ' Success:  An email with reset link has been sent to '. $email. ' '.'url:'.$identity.'/'.$token_to_send
                                )
                            );
                        }else{
                            $this->jsonResponse(
                                array(
                                    'success' => false, 
                                    'code' => Controller::HTTP_SERVER_ERROR_CODE, 
                                    'messages' => 'Unexpected error: Enter email and try again'
                                )
                            );
                        }
                        
                    }else{
                        /**
                         * Update token and identity
                         * Note: token and identity changes on subsequent request
                         */

                        
                        if(ForgotPasswordModel::updateRequest($this->pdoConnection, $name, $email, $special_id, $request, $request_date_time, $expires, $identity, $token, $token_to_send)){
                            /**
                             * Send a mail at this point befor the
                             */
                            $this->jsonResponse(
                                array(
                                    'success' => true, 
                                    'code' => Controller::HTTP_OKAY_CODE, 
                                    'messages' => ' Success:  An email with reset link has been resent to '. "<span class = 'text-primary'>".$email."</span>" . "\r\n" ." Note: Your initial reset link has been rendered invalid ".' '.'url:'.$identity.'/'.$token_to_send
                                )
                            );
                        }else{
                            $this->jsonResponse(
                                array(
                                    'success' => false, 
                                    'code' => Controller::HTTP_SERVER_ERROR_CODE, 
                                    'messages' => 'Unexpected error: Enter email and try again'
                                )
                            );
                        }
                    }
                }else{
                    $response = $this->jsonResponse(
                        array(
                            'success' => false, 
                            'code' => Controller::HTTP_UNAUTHORIZED_CODE, 
                            'messages' => 'Access denied'
                        )
                    ); 
                }
            }else if($request == 'reset_password'){
                /**
                 *  Reset password logic
                 */
                $password = $_POST['password'];
                $passwordDuplicate = $_POST['passwordDuplicate'];
                $identity = $_SESSION['identity'];
                $user = new ForgotPasswordModel();
                $specialId = $user->getSpecialIdFromTempRequest($this->pdoConnection, $identity);
                if($specialId == 'Server error'){
                    $this->jsonResponse(
                        array(
                            'success' => false, 
                            'code' => Controller::HTTP_SERVER_ERROR_CODE, 
                            'messages' => 'Server error'
                        )
                    );
                }else{
                    /**
                     * Delete request
                     */
                    $delete = $user->deleteUserRequest($this->pdoConnection, $identity);
                    if($delete === true){
                        /**
                         * Update password
                         */
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $specialId = $specialId['special_id'];
                        /**
                         * Check if password has been used before
                         */
                        $userPassword = $user->getUserPassword($this->pdoConnection, $specialId);
                        if($userPassword == 'Server error'){
                            $this->jsonResponse(
                                array(
                                    'success' => false, 
                                    'code' => Controller::HTTP_SERVER_ERROR_CODE, 
                                    'messages' => 'Server error1'
                                )
                            );
                        }else{
                            if(password_verify($_POST['password'],$userPassword['password'])){
                                $this->jsonResponse(
                                    array(
                                        'success' => false, 
                                        'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                                        'messages' => "Password have been used, type a new password"
                                    
                                    )
                                );
                            }
                        }
                        
                            

                        $updatePassword = $user->UpdatePassword($this->pdoConnection, $specialId, $password);
                        if($updatePassword === true){
                            $user->deleteUserRequest($this->pdoConnection, $identity);
                            $_SESSION = [];
                            $this->jsonResponse(
                                array(
                                    'success' => true, 
                                    'code' => Controller::HTTP_OKAY_CODE, 
                                    'messages' => "Success: Password Reset Successfully",
                                    'url' => '/login'
                                )
                            );
                        }else if($updatePassword == 'Server error'){
                            $this->jsonResponse(
                                array(
                                    'success' => false, 
                                    'code' => Controller::HTTP_SERVER_ERROR_CODE, 
                                    'messages' => 'Server error1'
                                )
                            );
                        }else{
                            $this->jsonResponse(
                                array(
                                    'success' => false, 
                                    'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                                    'messages' => "Unexpected Error",
                                
                                )
                            );
                        }
                    }else if($delete == 'Server error'){
                        $this->jsonResponse(
                            array(
                                'success' => false, 
                                'code' => Controller::HTTP_SERVER_ERROR_CODE, 
                                'messages' => $specialId
                            )
                        );
                    }else{
                        $this->jsonResponse(
                            array(
                                'success' => false, 
                                'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                                'messages' => "Unexpected Error",
                            
                            )
                        );
                    }
                // 
            }
                
            } 
        }
    }

    private function setSession($info){
        $_SESSION = $info;
    }
}
?>