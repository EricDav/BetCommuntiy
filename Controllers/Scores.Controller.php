<?php 
    class ScoresController extends Controller {
        public function __construct($request) {
            parent::__construct($request);
        }

        public function perform() {

        }

        public function validate() {

        }

        public function update() {
            $this->pdoConnection->open();
            $fileName = 'live.json';
            $data = json_decode(file_get_contents("php://input"))->data;
            $data = json_decode($data);
            $result = array();
            if (file_exists($fileName)) {
                $oldData = json_decode(file_get_contents($fileName));
                $finishedData = $this->getFinishedResults($data);
                $finishedOldData = $this->getFinishedResults($oldData);

                foreach($finishedData as $new) {
                    $found = false;
                    foreach($finishedOldData as $old) {
                        if ($new->match_id == $old->match_id) {
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        array_push($result, $new);
                    }
                }

            } else {
                $result = $this->getFinishedResults($data);
            }

            if (sizeof($result) > 0) {
                if (ScoresModel::createScore($this->pdoConnection, $result)) {
                    $this->jsonResponse(['success' => true, 'message' => 'New results created'], 200);
                }
            }

            file_put_contents($fileName, json_encode($data));
            $this->jsonResponse(['success' => true, 'message' => 'No score update'], 200);
        }

        public function getFinishedResults($data) {
            $finishedMatches = array();
            foreach($data as $datum) {
                if ($datum->time == 'Finished') {
                    array_push($finishedMatches, $datum);
                }
            }
            return $finishedMatches;
        }

        public function jsonResponse($response, $statusCode) {
            header('Content-Type: application/json');
            header("HTTP/1.0 " . (string)$statusCode . " ");
            echo json_encode($response);
            exit;
        }
    }
?>