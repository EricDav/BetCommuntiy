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
    CONST HTTP_UNAUTHORIZED_CODE = 401;

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