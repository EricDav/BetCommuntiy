<?php

    class HomeController extends Controller {
        public $offset;
        public function  __construct($request) {
            parent::__construct($request);
            $this->pdoConnection->open();
            $this->pageNum = isset($this->request->query['page']) ? $this->request->query['page'] : Controller::DEFAULT_OFFSET;
        }

        public function validate() {
            if (is_numeric($this->pageNum)) {
                $this->offset = ($this->pageNum - 1) * Controller::DEFAULT_LIMIT;
                return true;
            }

            return false;
        }

        public function perform() {
            if ($this->request->method == 'GET') {
                $this->pdoConnection->open();
                $isProblemWhileFecthingData = false;
                $followers = array();

                $predictions = CreatePredictionModel::getPredictions($this->pdoConnection, Controller::DEFAULT_LIMIT, $this->offset);
                if (!$prediction && !is_array($predictions)) {
                    $isProblemWhileFecthingData = true;
                }
                //     $isProblemWhileFecthingData = true;
                
                // var_dump(explode('\\n', $predictions[0]['prediction'])); exit; 
                if ($this->isLogin()) {
                   //  var_dump(); exit;
                    $followers = UserModel::getFollowers($this->pdoConnection, $this->request->session['userInfo']['id']);
                    //if (!$followers)
                      //  $isProblemWhileFecthingData = true;
                }


                if (!$isProblemWhileFecthingData) {
                    $this->data['followers'] = $follower;
                    $this->data['predictions'] = $predictions;
                    $this->setDateCreatedUTC($predictions);
                    $this->data['template'] = 'Home.php';
                    $this->data['title'] = 'Home';
                    $this->responseType = 'html';
                }

            } else {
                $this->data['template'] = '404.php';
                $this->data['title'] = '404';
                $this->responseType = 'html';        
            }
            $this->setToken();
        }

        public function setDateCreatedUTC($predictions) {
            $dates = array();
            foreach($predictions as $prediction) {
                array_push($dates, array('date_created' => $prediction['created_at'], 'id' => $prediction['id']));
            }
            $this->data['dates'] = $dates;
        }

        public function isFollowing($userId) {
            if (sizeof($data['followers']) == 0) 
                return false;
            foreach($data['followers']  as $follower) {
                if ($userId == $follower['user_id']) 
                    return true;
            }
        }
    }

?>