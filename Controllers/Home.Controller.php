<?php

    class HomeController extends Controller {
        const LOST_PREDICTION_QUERY = 1;
        const CORRECT_PREDICTION_QUERY = 2;
        const INPROGRESS_PREDICTION_QUERY = 3;
        const HOME_FILTER = 4;
        const CORRECT_FILTER = 5;
        const LOST_FILTER = 6;
        const INPROGRESS_FILTER = 7;

        public $offset;
        public function  __construct($request) {
            parent::__construct($request);
            $this->pdoConnection->open();
            $this->pageNum = isset($this->request->query['page']) ? $this->request->query['page'] : Controller::DEFAULT_OFFSET;
            $this->query = isset($this->request->query['filter_option']) ? $this->request->query['filter_option'] : '';
            $this->isOddsFilter = ($this->query != '' && $this->query != (string)self::CORRECT_PREDICTION_QUERY && $this->query != (string)self::LOST_PREDICTION_QUERY 
                 && $this->query != (string)self::INPROGRESS_PREDICTION_QUERY) ? true : false;
            
        }

        public function validate() {
            /**
             * Validates odds filter in this form min_max
             * where min and max are numbers. it confirms if both 
             * are numbers if not return false
             */
            if ($this->isOddsFilter) {
                $minMax = explode('_', $this->query);
                if (sizeof($minMax) > 2 || !is_numeric($minMax[0]) || !is_numeric($minMax[1]))
                    return false;
            }

            /**
             * Validates for predictions query checks if
             * the query are both win and lost
             */
            if ($this->query && !$this->isOddsFilter) {
                if (!is_numeric($this->query))
                    return false;
                if ((int)$this->query != self::CORRECT_PREDICTION_QUERY && (int)$this->query != self::LOST_PREDICTION_QUERY
                    && (int)$this->query != self::INPROGRESS_PREDICTION_QUERY) {
                    return false;
                }
            }

            /**
             * Validates that page number is numeric
             */
            if (!is_numeric($this->pageNum))
                return false;

            $this->offset = ($this->pageNum - 1) * Controller::DEFAULT_LIMIT;
            return true;
        }

        public function perform() {
            if ($this->request->method == 'GET') {
                $this->pdoConnection->open();
                $isProblemWhileFecthingData = false;
                $followers = array();

                $predictions = PredictionModel::getPredictions($this->pdoConnection, Controller::DEFAULT_LIMIT, $this->offset, $this->query, $this->isOddsFilter);
                $featuredUsers = UserModel::getFeaturedUsers($this->pdoConnection);

                if (!$predictions && !is_array($predictions)) {
                    $isProblemWhileFecthingData = true;
                }

                if (!$featuredUsers && !is_array($predictions)) {
                    $isProblemWhileFecthingData = true;
                }
                
                if ($this->isLogin()) {
                    $followers = UserModel::getFollowers($this->pdoConnection, $this->request->session['userInfo']['id']);
                    //if (!$followers)
                      //  $isProblemWhileFecthingData = true;
                }

                if (!$isProblemWhileFecthingData) {
                    $this->data['followers'] = $followers;
                    $this->data['predictions'] = $predictions;
                    $this->data['featuredUsers'] = $featuredUsers;
                    $count = $predictions ? $predictions[0]['total'] : 0; // total number of predictions in the system
                    $this->data['paginationHtml'] = $this->generatePaginationNum($count, $this->pageNum);
                    $this->setDateCreatedUTC($predictions);
                    $this->data['template'] = 'Home.php';
                    $this->data['title'] = 'Home';
                    $this->data['predictionLostQuery'] = self::LOST_PREDICTION_QUERY;
                    $this->data['predictionWonQuery'] = self::CORRECT_PREDICTION_QUERY;
                    $this->data['predictionInprogressQuery'] = self::INPROGRESS_PREDICTION_QUERY;
                    $this->data['homeNum'] = self::HOME_FILTER;
                    $this->data['correctNum'] = self::CORRECT_FILTER;
                    $this->data['lostNum'] = self::LOST_FILTER;
                    if ($this->isOddsFilter) {
                        $minMax = explode('_', $this->query);
                        $this->data['min'] = $minMax[0];
                        $this->data['max'] = $minMax[1];
                    } else {
                        $this->data['min'] = '';
                        $this->data['max'] = '';                       
                    }
                    $this->data['inprogressNum'] = self::INPROGRESS_FILTER;
                    $this->responseType = 'html';
                }

            } else {
                $this->data['template'] = '404.php';
                $this->data['title'] = '404';
                $this->responseType = 'html';        
            }
            $this->setToken();
        }

        public function getFeaturedUsers() {
            $featuredUsers = UserModel::getFeaturedUsers($this->pdoConnection);
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

        /**
         * This function generates the pagination number html
         * e.g <Previous |1|2|3|4|5 | Next >
         * 
         * @param count total number of predictions
         * @param page number the current page number 
         * 
         */
        public function generatePaginationNum($count, $pageNum) {
            if ($count == 0) return ''; // Returns empty if there is no predictions

            $quo = (int)($count/20); // Calculate the quotient
            $num =  $count%20 == 0 ? $quo : $quo + 1; // calculate number of paginations

            if ($num == 1) // If just one pagination returns empty string no pagination.
                return '';
            
            $min = 0; // Initialize the minimum pagination
            $max = 1; // Initialize the maximum pagination

            $html = '<nav aria-label="Page navigation example">' 
                       . '<ul class="pagination">';
            
            if ($count <= 100 || $pageNum < 4) { // This handles pagination with highest max of 5 and the min of 1
                $min = 1; // Set the minimum to 1 

                // Set the maximum value if pagination is less than or equals 
                // to 5 just set the max to the pagination, since it is within the range
                // of 5 pagination else just set to 5 with hidden paginations. This pagination will
                //  be revealed when a user clicks above page 3
                $max = $num <= 5 ? $num : 5; 
            } else {
                if ($count - 2 >= $pageNum) {
                    $min = $pageNum - 2;
                    $max = $pageNum + 2;
                } else {
                    $min = $pageNum - 3;
                    $max = $pageNum + 1;                   
                }
            }

            $html = $pageNum != $min ? ($html . '<li class="page-item"><a class="page-link" href="#">Previous</a></li>') : $html;
            for ($i = $min; $i <= $max; $i++) {
                $html.= '<li class="page-item"><a class="page-link" href="#">' . $i . '</a></li>';
            }
            $html = $pageNum != $max ? ($html . '<li class="page-item"><a class="page-link" href="#">Next</a></li>') : $html;

            $html = $html . ' </ul>' . '</nav>';
            return $html;
        }

        /**
         * It helps to highlight the current filter
         */
        public function formatFilterText($text, $filterNum) {
            switch ($filterNum) {
                case self::HOME_FILTER:
                    if (!$this->query || $this->isOddsFilter)
                        return '<b style="color:black;">' . $text . '</b>';
                    return $text;
                case self::CORRECT_FILTER:
                    if (!$this->isOddsFilter && (int)$this->query == self::CORRECT_PREDICTION_QUERY)
                        return '<b style="color:black;">' . $text . '</b>';
                    return $text;
                case self::LOST_FILTER:
                    if (!$this->isOddsFilter && (int)$this->query == self::LOST_PREDICTION_QUERY)
                        return '<b style="color:black;">' . $text . '</b>';
                    return $text;
                case self::INPROGRESS_FILTER:
                    if (!$this->isOddsFilter && (int)$this->query == self::INPROGRESS_PREDICTION_QUERY)
                        return '<b style="color:black;">' . $text . '</b>';
                    return $text;
            }           
        }
    }

?>