<?php
class NotificationController extends Controller{
    protected $manual = [];
    public $request;
    public $pdo = null;

    public function __construct(){
        $this->request = new Request();
        $this->manual = $this->request->postData;
        $pdoClass = new PDOConnection();
        $pdoClass->open();
        $this->pdo = $pdoClass->pdo;
        session_regenerate_id(true);
    }

    public function validate(){
        if($this->request->method == 'POST'){
            return true;
        }else{
            return false;
            $this->error = 'Invalid request';
            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_UNAUTHORIZED_CODE, 'messages' => $this->error));
        }
    }

    public function perform(){
        if($this->manual['type'] == 'getNotification'){
            NotificationModel::getNotification($this->pdo, $this->manual);
        }else if($this->manual['type'] == 'registerUserNotification'){
            NotificationModel::registerUserNotification($this->pdo, $this->manual);
        }
    }
}
?>


