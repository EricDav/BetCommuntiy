<?php 
    class UserProfileController extends Controller {
        const DEFAULT_ADD_PROFILE = 180191;
        public function __construct($request) {
            parent::__construct($request);
            $this->clientId = $request->query['id']; // The sum of the default and the user id e.g if the user id is 1 the client id will be 180192
            $this->user = null;
        }

        public function validate() {
            if (!$this->clientId || !is_numeric($this->clientId)) {
                $this->data['template'] = '404.php';
                $this->data['title'] = '404';
                return false;
            }
            
            return true;
        }

        public function perform() {
            $this->pdoConnection->open();
            $followers = array();
            $userId = (int)$this->clientId - self::DEFAULT_ADD_PROFILE;
            // echo $userId; exit;
            $followers = UserModel::getUsersFollowers($this->pdoConnection, $userId);
            // var_dump($followers); exit;
            $user = UserModel::getUserById($this->pdoConnection, $userId);
            // var_dump($user); exit;
            $names = explode(' ', $user[0]['name']);
            $this->user = $user;
            $competitions = json_decode(file_get_contents(__DIR__  . '/../JsonData/competitions.json'));

            if (is_array($user) && !$user) {
                $this->data['template'] = '404.php';
                $this->data['title'] = '404';
                return;
            }


            if (!is_array($user)) {
                // 500 error page;
            }

            $approved = $this->isSelf() ? null : 1; // Retrieve both approved and unapproved prediction if isSelf else only approved
            $predictions = PredictionModel::getPredictionsByUserId($this->pdoConnection, $user[0]['id'], $approved);
            $this->data['user'] = $user;
            $this->data['firstName'] = $names[0];
            $this->data['lastName'] = $names[1];
            $this->data['predictions'] = $predictions;
            $this->data['followers'] = $followers;
            $this->data['isFollowing'] = $this->isFollowing(); // checks if the current user is following the user we are checking the profile
            $this->data['template'] = 'Profile.php';
            $this->data['title'] = '4CastBet | Profile';
            $this->data['supportedBettingPlatforms'] = BetGamesController::SUPPORTED_BETTING_PLATFORMS;
            $this->data['isSelf'] = $this->isSelf();
            $this->data['followingText'] = $this->getNumberFollowingText($user[0]['num_followers'], $user[0]['sex']);
            $this->data['competitions'] = $competitions->data->competition;
            $this->data['outcomes'] = BetCommunity::OUTCOMES;
            $this->responseType = 'html';
            $this->setDateCreatedUTC($predictions);
            $this->setPredictionInfo($predictions);
        }

        /**
         * This function overides the isFollowing method in 
         * the Controller class. This checks if the current user 
         * is a follower of the user we are checking it's profile
         * 
         */
        public function isFollowing($userId=null) {
            if ($this->isSelf() || !$this->isLogin()) {
                return false;
            }

            $data = $this->data;
            if (sizeof($data['followers']) == 0) 
                return false;
            foreach($data['followers']  as $follower) {
                if ($this->request->session['userInfo']['id'] == $follower['follower_id']) 
                    return true;
            }
    
            return false;
        }

        /**
         * This function checks if the user is checking is 
         * own profile picture.
         */
        public function isSelf() {
            return $this->isLogin() && $this->request->session['userInfo']['id'] == $this->user[0]['id'];
        }

        public function getNumberFollowingText($numFollowers, $sex) {
            if ($numFollowers == 0) 
                return '';
            
            $numFollwers = (string)$numFollowers;
            $text = $numFollwers . ($numFollwers == 1 ? ' person is' : ' people are') . ' following ';
            if ($this->isSelf())
                return $text . ' you';
            if ($this->isLogin() && !$this->isSelf()) {
                // 
            }
            if ($sex == 'M') {
                return $text . ' him';
            } else {
                return $text . ' her';
            }
        }
    }
?>
