<?php
class ContactController extends Controller{

    public $data = [];

    public function  __construct($request) {
        parent::__construct($request);
        $this->pdoConnection->open();
    }

    public function validate(){
        if ($this->request->method == 'GET') { 
            return true;
        }else if($this->request->method == 'POST'){
        
            $field_indicator = 0;
            $this->data = $_POST['data'];
            foreach($this->data as $field => $value){
                /**
                 * check if empty
                 */
                if($this->isEmpty($value)){
                    $this->jsonResponse(
                        array(
                            'success' => false, 
                            'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                            'message' => $field . " cannot be left empty",
                            "field_index" => $field_indicator
                        )
                    );
                }


                $value = htmlspecialchars($value, ENT_QUOTES);


                /**
                 * Validate name sting
                 */
                if($field == 'Full Name'){
                    $name = new Validation();
                    $valid_result = $name->isValidName($value);
                    if($valid_result['isValid'] == false){
                        $this->jsonResponse(
                            array(
                                'success' => false, 
                                'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                                'message' => $valid_result['message'],
                                "field_index" => $field_indicator
                            )
                        );
                    }else if($valid_result['isValid'] == true){
                        $this->data['name'] = $value;
                    } 
                }
                
                /**
                 * Validate email
                 */
                if($field == 'Email'){
                    if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                        $this->jsonResponse(
                            array(
                                'success' => false, 
                                'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                                'message' => 'Invalid email address',
                                "field_index" => $field_indicator
                            )
                        );
                        exit;
                    }else{
                        $this->data['email'] = $value;
                    }
                   
                }  

                 /**
                 * Validate email
                 */
                if($field == 'Message'){
                    $this->data['message'] = $value;
                }  
                $field_indicator++;
            }
            return true;
        }else{
            return false;
        }
    }

    public function perform(){
        if ($this->request->method == 'GET') {
            $this->data['template'] = 'Contact.php';
            $this->data['title'] = '4CastBet | Contact Us';
            $this->responseType = 'html';
        }else if($this->request->method == 'POST'){
            $time = new DateTime('now');
            $request_time = $time->format('Y-m-d, h:i:s');
            $name = filter_var($this->data['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($this->data['email'], FILTER_SANITIZE_EMAIL);
            $message = filter_var($this->data['message'], FILTER_SANITIZE_STRING);
            $isAUser = false;
            $userId = '';
            if($this->isLogin()){
                $isAUser = true;
                $userId = $_SESSION['userInfo']['id'];
            }
            /**
             * Check if message exist with the same message content and name
             */
            $contact = new ContactModel();
            $return = $contact->doesMessageExist($name, $email, $message, $isAUser, $userId, $this->pdoConnection);
            if($return === 'Server error'){
                $this->jsonResponse(
                    array(
                        'success' => false, 
                        'code' => Controller::HTTP_SERVER_ERROR_CODE, 
                        'message' => $return
                    )
                );
                exit;
            }else if($return === true){
                $this->jsonResponse(
                    array(
                        'success' => false, 
                        'code' => Controller::HTTP_BAD_REQUEST_CODE, 
                        'message' => 'You sent this message earlier',
                        "field_index" => 2
                    )
                );
                exit;
            }else{
                $register_contact_message = $contact->storeContactMessage($name, $email, $message, $isAUser, $userId, $request_time, $this->pdoConnection);
                if($register_contact_message !== false && $register_contact_message !== true){
                    
                    $this->jsonResponse(
                        array(
                            'success' => false, 
                            'code' => Controller::HTTP_SERVER_ERROR_CODE, 
                            'message' => $register_contact_message,
                        )
                    );
                }else{
                    if($register_contact_message === true){
                        $this->jsonResponse(
                            array(
                                'success' => true, 
                                'code' => Controller::HTTP_OKAY_CODE, 
                                'message' => 'Message successfully registered',
                            )
                        );
                    }else{
                        $this->jsonResponse(
                            array(
                                'success' => true, 
                                'code' => Controller::HTTP_OKAY_CODE, 
                                'message' => 'Something went wrong, try again!',
                            )
                        );
                    }
                }
            }   
        }else{
            return false;
        }
    }

    public function isEmpty($value){
        if(empty($value)){
            return true;
        }
        return false;
    }
}
?>