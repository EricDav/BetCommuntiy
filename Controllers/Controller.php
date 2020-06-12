<?php
abstract class Controller {
    public $data;
    private $template;
    public $request;
    public $responseType;
    public $pdoConnection;
    public $error;

    const HTTP_BAD_REQUEST_CODE = 400;
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
    }


    public function setData($data) {
        $this->data = $data;
    }

    public function setToken() {
        if ($this->isLogin()) {
            // var_dump('I got here'); exit;
            $token = bin2hex(openssl_random_pseudo_bytes(16));
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

    abstract function validate();

    abstract function perform();
}
?>