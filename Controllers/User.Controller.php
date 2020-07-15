<?php 
    /**
     * @routes /api/web/users-action
     * 
     * Handles only POST REQUEST for the route above.
     * It also requires authentication, user must be be login to access 
     * this route.
     * 
     * @param action_type either `follow` or `like`
     * @param  user_id  id of the user the current user want to follow or the id of the user liking a prediction
     * @param prediction_d only needed when action_type is `like`
     * @param is_following only needed when action_type is `follow` basically used to 
     * know weather to follow or unfollow it two possible values `0` not following `1` for following
     * 
     * @return It returns a json_response to the client
     * 
     */
    class UserController extends Controller {
        public function __construct($request) {
            parent::__construct($request);
            $this->actionType = isset($this->request->postData['action_type']) ? $this->request->postData['action_type'] : '';
        }

        public function validate() {
            $this->authenticate();
            if (!$this->actionType) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Bad request no action type'));
            }

            if ($this->actionType == 'like') {
                if (isset($this->request->postData['user_id']) && is_numeric($this->request->postData['user_id']) && isset($this->request->postData['prediction_id'])
                    && is_numeric($this->request->postData['prediction_id']))
                    return true;
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Bad request bad parameters'));
            }

            if ($this->actionType == 'follow') {
                if ($this->request->postData['user_id'] == $this->request->session['userInfo']['id']) {
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Bad request can not follow yourself'));
                }
                
                if (isset($this->request->postData['user_id']) && is_numeric($this->request->postData['user_id']) && isset($this->request->postData['is_following'])
                    && is_numeric($this->request->postData['is_following'])) {
                        if ((int)$this->request->postData['is_following'] == 0 || (int)$this->request->postData['is_following'] == 1)
                            return true;
                }
                    
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Bad request bad parameters'));
            }

            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Bad request, invalid action type'));
        }

        public function perform() {
            $this->pdoConnection->open();
            if ($this->actionType == 'like') {
                if (UserModel::like($this->pdoConnection, $this->request->postData['prediction_id'], 
                    $this->request->postData['user_id'])) {
                    $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'messages' => 'Prediction liked successfully'));
                }

                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            }
           // var_dump($this->request->postData['is_following'] == 1); exit;

            /**
             * If user want to follow another user. which means
             * the user shouldn't be following the other user
             */
            if ($this->actionType == 'follow' && $this->request->postData['is_following'] == 0) {
                $follower = UserModel::getFollower($this->pdoConnection, $this->request->session['userInfo']['id'], 
                    $this->request->postData['user_id']);
                
                if ($follower === false)
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));

                    
                if (is_array($follower) && sizeof($follower) > 0)
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Already following'));

                $followersCount = UserModel::getUserFollowersCount($this->pdoConnection, $this->request->postData['user_id']);
                
                if ($followersCount['total_followers'] >= 1000) {
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'User already reached maximum followers'));
                }

                if (is_array($follower) && sizeof($follower) > 0)
                    $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Already following'));
                
                if (UserModel::addFollower($this->pdoConnection, $this->request->session['userInfo']['id'], 
                    $this->request->postData['user_id'])) {
                        $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'messages' => 'Followed successfully'));
                }

                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            }

            /**
             * if user want to unfellow another user. This means
             * the user should currently be following the other user
             */
            if ($this->actionType == 'follow' && $this->request->postData['is_following'] == 1) {
                if (UserModel::removeFollower($this->pdoConnection, $this->request->session['userInfo']['id'], 
                    $this->request->postData['user_id'])) {
                        $this->jsonResponse(array('success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'messages' => 'UnFollowed successfully'));
                }

                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));
            }
        }

        public function logout() {
            if ($this->request->method != 'GET') {
                // 500 error
            } 

            if (!$this->isLogin()) {
                header('Location: /');
            } else {
                session_destroy();
                header('Location: /');
            }
        }

        public function getForcasters() {
            if ($this->request->method == 'GET') {
                $followers = array();
                $this->pdoConnection->open();
                $featuredUsers = UserModel::getFeaturedUsers($this->pdoConnection);
                $forcasters = UserModel::getAllUsers($this->pdoConnection);
                if ($this->isLogin()) {
                    $followers = UserModel::getFollowers($this->pdoConnection, $this->request->session['userInfo']['id']);
                }

                $this->data['followers'] = $followers;
                $this->data['featuredUsers'] = $featuredUsers;
                $this->data['template'] = 'Forcasters.php';
                $this->data['title'] = 'Forcasters';
                $this->data['forcasters'] = $forcasters;
            }
        }
    }
?>
