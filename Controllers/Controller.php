<?php
abstract class Controller {
    private $data;
    private $template;
    public $request;
    public $responseType;

    public function __construct($request) {
        $this->data = array();
        $this->template = null;
        $this->request = $request;
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

    abstract function validate();

    abstract function perform();
}
?>