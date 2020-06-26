<?php
    /**
     * This class is responsible for fetching the games 
     * of some betting platform.
     */
    class BetGamesController extends Controller {
        const PLATFORM_BET9JA = 'Bet9ja';
        const PLATFORM_BETKING = 'BetKing';
        const PLATFORM_SPORTY_BET = 'SportyBet';

        const SUPPORTED_BETTING_PLATFORMS = array(self::PLATFORM_BET9JA, self::PLATFORM_BETKING, self::PLATFORM_SPORTY_BET);

        public function __construct($request) {
            parent::__construct($request);
            $this->bookingCode = $request->query['code'] ? $request->query['code'] : '';
            $this->betType = $request->query['type'] ? $request->query['type'] : '';
        }

        public function validate() {
            $this->authenticate();

            if (!$this->bookingCode || !$this->betType) {
                return false;
            }

            if (!in_array($this->betType, self::SUPPORTED_BETTING_PLATFORMS)) {
                return false;
            }

            return true;
        }

        public function perform() {
            switch ($this->betType) {
                case self::PLATFORM_BETKING:
                    $data = json_decode(file_get_contents('https://sportsapi.betagy.services/api/BetCoupons/Booked/' . $this->bookingCode . '/en'));
                    break;
                case self::PLATFORM_SPORTY_BET:
                    $data = json_decode(file_get_contents('https://www.sportybet.com/api/ng/orders/share/' . $this->bookingCode));
                    break;

            }
           
            if ($data) {
                $this->jsonResponse(array('success' => true, 'data' => $data, 'code' => Controller::HTTP_OKAY_CODE));
            }
            $this->jsonResponse(array('success' => false, 'code' => Controller::HTTP_SERVER_ERROR_CODE, 'message' => 'Something unexpected happen could not retrieve data'));
        }
    }
?>
