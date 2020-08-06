<?php

    class HomeController extends Controller {
        const LOST_PREDICTION_QUERY = 1;
        const CORRECT_PREDICTION_QUERY = 2;
        const INPROGRESS_PREDICTION_QUERY = 3;
        const HOME_FILTER = 4;
        const CORRECT_FILTER = 5;
        const LOST_FILTER = 6;
        const INPROGRESS_FILTER = 7;
        const FORCASTERS_FILTER = 8;
        const NUM_SECONDS_PERDAY = 86400;
        const PENDING_OUTCOMES_ROUTE = '/predictions/pending-outcomes';
        const MY_APPROVED_OUTCOMES_ROUTE = '/predictions/my/approved-outcomes';

        public $offset;
        public function  __construct($request) {
            parent::__construct($request);
            $this->pdoConnection->open();
            $this->pageNum = isset($this->request->query['page']) ? $this->request->query['page'] : Controller::DEFAULT_OFFSET;
            $this->queryDay = isset($this->request->query['filter_day']) ? $this->request->query['filter_day'] : null;
            $this->currentDate = isset($this->request->query['current_date']) ? $this->request->query['current_date'] : null; // current date in client timezone
            $this->startDateInUTC = null; // This is the start date range weather today's, yesterday's or weekend games. 
            $this->endDateInUTC = null;  // This is the end date range weather today's, yesterday's or weekend games.
            $this->predictionStatus = isset($this->request->query['filter_status']) ? $this->request->query['filter_status'] : null;
            $this->currentSideBarFilter = 'All Predictions';
            $this->isOddsFilter = null;
            $this->isPendingOutcomes = false;
            $this->myApprovedUserId = null;
            $this->pendingUserId = null;
        }

        public function validate() {
            /**
             * Validates odds filter in this form min_max
             * where min and max are numbers. it confirms if both 
             * are numbers if not return false
             */
            if ($this->isOddsFilter) {
                $minMax = explode('_', $this->query);
                if (sizeof($minMax) > 2 || !is_numeric($minMax[0]) || !is_numeric($minMax[1])) {
                    $this->set404Page();
                    return false;
                }
            }

            if ($this->predictionStatus) {
                if ($this->predictionStatus != 'won') {
                    $this->predictionStatus = null;
                } else {
                    $this->currentSideBarFilter = 'Correct Predictions';
                }
            }

            if ($this->queryDay) {
                 //echo $this->currentDate; exit;
                 $dateArr = explode(' ', $this->currentDate);
                 // var_dump($dateArr); exit;

                $beginingDate = $dateArr[0] . ' ' . '00:00';
                $endingDate = $dateArr[0] . ' ' . '23:59';
               // var_dump($dateArr);
               // var_dump($this->isValidateDateTime($dateArr[0], $dateArr[1])); exit;

                if ($this->isValidateDateTime($dateArr[0], $dateArr[1])) {
                    if ($this->queryDay == 'yesterday') {
                        $beginingDateDiff = $this->getMinutesDiff($this->currentDate,  $beginingDate);
                        $timeInUTC = strtotime(gmdate("Y-m-d\ H:i:s"));

                        $startTimeUTC = $timeInUTC - ($beginingDateDiff * 60) - self::NUM_SECONDS_PERDAY;
                        $endTimeUTC = $timeInUTC - ($beginingDateDiff * 60);

                        $startDateInUTC = date("Y-m-d H:i:s", $startTimeUTC);
                        $endDateInUTC = date("Y-m-d H:i:s", $endTimeUTC);

                        $this->startDateInUTC = $startDateInUTC;
                        $this->endDateInUTC  = $endDateInUTC;
                        $this->currentSideBarFilter = 'Yesterday Predictions';
                    }

                    if ($this->queryDay == 'today') {

                        $beginingDateDiff = $this->getMinutesDiff($this->currentDate,  $beginingDate);
                        $endingDateDiff = $this->getMinutesDiff($this->currentDate,  $endingDate);
                        $timeInUTC = strtotime(gmdate("Y-m-d\ H:i:s"));

                        $startTimeUTC = $timeInUTC - ($beginingDateDiff * 60);
                        $endTimeUTC = $timeInUTC + ($endingDateDiff * 60);

                        $startDateInUTC = date("Y-m-d H:i:s", $startTimeUTC);
                        $endDateInUTC = date("Y-m-d H:i:s", $endTimeUTC);

                        $this->startDateInUTC = $startDateInUTC;
                        $this->endDateInUTC  = $endDateInUTC;
                        // var_dump($this->endDateInUTC); exit;
                        $this->currentSideBarFilter = 'Today Predictions';
                    }
                }
            }

            /**
             * Validates for predictions query checks if
             * the query are both win and lost
             */
            // if ($this->query && !$this->isOddsFilter) {
            //     if (!is_numeric($this->query))
            //         return false;
            //     if ((int)$this->query != self::CORRECT_PREDICTION_QUERY && (int)$this->query != self::LOST_PREDICTION_QUERY
            //         && (int)$this->query != self::INPROGRESS_PREDICTION_QUERY) {
            //         return false;
            //     }
            // }

            /**
             * Validates that page number is numeric
             */
            if (!is_numeric($this->pageNum)) {
                $this->pageNum = Controller::DEFAULT_OFFSET;
            }

            if ($this->request->route == self::PENDING_OUTCOMES_ROUTE) {
                if (isset($this->request->session['userInfo']) && $this->request->session['userInfo']['role'] > 1) {
                    $this->isPendingOutcomes = true;
                    $this->currentSideBarFilter = 'Pending Predictions Outcomes';

                } else {
                    $this->set404Page();
                    return false;
                }

            } else if ($this->request->route == self::MY_APPROVED_OUTCOMES_ROUTE) {
                if (isset($this->request->session['userInfo']) && $this->request->session['userInfo']['role'] > 1) {
                    $this->pendingUserId = $this->request->session['userInfo']['id'];
                    $this->currentSideBarFilter = 'My Approved Outcomes';

                } else {
                    $this->set404Page();
                    return false;
                }
            } 
            
            

            $this->offset = ($this->pageNum - 1) * Controller::DEFAULT_LIMIT;
            return true;
        }

        public function perform() {
            if ($this->request->method == 'GET') {
                $competitions = json_decode(file_get_contents(__DIR__  . '/../JsonData/competitions.json'));
                
                $this->pdoConnection->open();
                $isProblemWhileFecthingData = false;
                $followers = array();

                $predictions = PredictionModel::getPredictions($this->pdoConnection, Controller::DEFAULT_LIMIT,
                    $this->offset, $this->startDateInUTC, $this->endDateInUTC, $this->predictionStatus, $this->isPendingOutcomes, $this->pendingUserId);

               // var_dump($predictions); exit;
                
                $featuredUsers = UserModel::getFeaturedUsers($this->pdoConnection);

                if (!$predictions && !is_array($predictions)) {
                    $isProblemWhileFecthingData = true;
                }

                if (!$featuredUsers && !is_array($predictions)) {
                    $isProblemWhileFecthingData = true;
                }
                
                if ($this->isLogin()) {
                    $followers = UserModel::getFollowers($this->pdoConnection, $this->request->session['userInfo']['id']);
                    // var_dump($followers); exit;
                }

                if (!$isProblemWhileFecthingData) {
                    $this->data['followers'] = $followers;
                    $this->data['predictions'] = $predictions;
                    $this->data['featuredUsers'] = $featuredUsers;
                    $count = $predictions ? $predictions[0]['total'] : 0; // total number of predictions in the system
                    $this->data['paginationHtml'] = $this->generatePaginationNum($count, $this->pageNum);
                    $this->setDateCreatedUTC($predictions);
                    $this->setPredictionInfo($predictions);
                    $this->data['template'] = 'Home.php';
                    $this->data['title'] = '4CastBet';
                    $this->data['supportedBettingPlatforms'] = BetGamesController::SUPPORTED_BETTING_PLATFORMS;
                    $this->data['competitions'] = $competitions->data->competition;
                    $this->data['outcomes'] = BetCommunity::OUTCOMES;
                    $this->responseType = 'html';
                }

            } else {
                $this->data['template'] = '404.php';
                $this->data['title'] = '404';
                $this->responseType = 'html';        
            }
        }

        public function getFeaturedUsers() {
            $featuredUsers = UserModel::getFeaturedUsers($this->pdoConnection);
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
           //  echo $count; exit;
            $baseUrl = $this->request->route;
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
                $html.= '<li class="' . ($pageNum == $i ? "page-item active" : "page-item") . '"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
            }
            $html = $pageNum != $max ? ($html . '<li class="page-item"><a class="page-link" href="#">Next</a></li>') : $html;

            $html = $html . ' </ul>' . '</nav>';
            return $html;
        }

        public function formatPredictionJson($predictionJson) {
            $predictions = json_decode($predictionJson);
        }
    }
?>
