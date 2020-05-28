<?php

class Request {
    public $method;
    public $query;
    public $postData;
    public $host;
    public $path; 
    // public $uri = $_SERVER['REQUEST_URI'];
    public $serverName;
    
    public function __construct() {
        $this->method =  $_SERVER['REQUEST_METHOD'];
        $this->host = $_SERVER['HTTP_HOST'];
        $this->query = $_GET;
        $this->postData = $_POST;
        $this->serverName = $_SERVER['SERVER_NAME'];
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];

        $this->route = $path;
    } 
}

?>