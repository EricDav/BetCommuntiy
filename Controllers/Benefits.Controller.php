<?php
class BenefitsController extends Controller{

    public function validate(){
        return true;
    }

    public function perform(){
        if ($this->request->method == 'GET') {
            $this->data['template'] = 'Benefit.php';
            $this->data['title'] = '4CastBet | Benefit';
            $this->responseType = 'html';
        }
    }

}