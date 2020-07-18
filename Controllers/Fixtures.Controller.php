<?php 
    class FixturesController extends Controller {
        public function __construct($request, $envObj) {
            parent::__construct($request, $envObj);
            $this->competitionId = isset($request->query['competition_id']) ? $request->query['competition_id'] : '';
        }

        public function validate() {
            $this->authenticate();
            if (!$this->competitionId) {
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_BAD_REQUEST_CODE, 'messages' => 'Invalid request, competition id is required'));
            }

            return true;
        }

        public function perform() {
            $results = array();
            if ($this->callApi()) {
                $page = 1;
                $data = json_decode(file_get_contents('https://livescore-api.com/api-client/fixtures/matches.json?key=' . $this->envObj->LIVESCORE_API_KEY . '&secret=' . $this->envObj->LIVESCORE_API_SECRET . '&competition_id=' . $this->competitionId));
                $next_page = $data->data->next_page;

                $data->data->next_page = null;
                $data->data->prev_page = null;
                array_push($results, $data->data);
                while (true) {

                    if (!$data) {
                        ErrorMail::Log('Fixture.Controller.php', '30', 'Error retrieving fixtures with competition id of' . $this->competitionId . ' from api. Mostly likely API failed');
                        break;
                        // log error like time
                    }

                    if ($page >= 100) {
                        ErrorMail::Log('Fixture.Controller.php', '36', 'Fixtures exceed expected page for for fixture id ' . $this->competitionId);
                        break;
                    }

                    if (!$next_page) {
                        break;
                    }
                    
                    $data = json_decode(file_get_contents($next_page));
                    $next_page = $data->data->next_page;
                    $data->data->next_page = null;
                    $data->data->prev_page = null;
                    array_push($results, $data->data);

                    $page+=1;
                }

                $this->writeToFiles($results);
                $this->jsonResponse(array('data' => $results, 'success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'messages' => 'Fixtures retrieved successfully'));
            }

            $path = __DIR__ . '/../JsonData/fixtures/competition-' . $this->competitionId;
            $results = json_decode(file_get_contents($path . '/fixture-competition-' . $this->competitionId . '.json'));
            
            // Unexpected error
            if (!$results) 
                $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Server error'));

            $this->jsonResponse(array('data' => $results, 'success' => true, 'code' => Controller::HTTP_OKAY_CODE, 'messages' => 'Fixtures retrieved successfully'));
        }

        public function callApi() {
            $path = __DIR__ . '/../JsonData/fixtures/competition-' . $this->competitionId;

            if (file_exists($path . '/fixture-competition-' . $this->competitionId . '.json')
                && file_exists($path . '/timestamp.txt')) {

                $dateTimeOfRecentFixtureUTC = file_get_contents($path . '/timestamp.txt');
                if ($dateTimeOfRecentFixtureUTC && $dateTimeOfRecentFixtureUTC > gmdate("Y-m-d\ H:i:s"))
                    return false;
                return true;
            }

            return true;
        }

        public function writeToFiles($results) {
            $path = __DIR__ . '/../JsonData/fixtures/competition-' . $this->competitionId;
            if (!file_exists($path)) {
                mkdir($path);
            }

            file_put_contents($path . '/timestamp.txt', $results[0]->fixtures[0]->date . ' ' .$results[0]->fixtures[0]->time);

            file_put_contents($path . '/fixture-competition-' . $this->competitionId . '.json',  json_encode($results));
        }
    }
?>
