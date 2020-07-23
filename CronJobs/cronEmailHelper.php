<?php
include 'SendMail.php';

function genererateNotificationEmailHtml($match, $dateCreated, $type, $betslip, $gamePos, $email) {
    $htScore = explode(' - ', $match->ht_score);
    $ftScore = explode(' - ', $match->score);
    // exit;
    $emailBody = '<head>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <style>
        @media screen and (max-width: 420px){
            .container{
                width: 100% !important;
            }
        }
        
        @media screen and (min-width: 420px){
            .container{
                width: 420px !important;
            }
        }
        @media screen and (max-width: 352px){
            .logo{
                width: 80%;
                height: auto
            }
        }
        tr:nth-child(even){
            background: #f2f2f2;
        }
    </style>
</head>
<!-- <meta http-equiv = "refresh" content = "1"> -->
<div style = "width: 100%; height: fit-content; display: flex; justify-content: center; align-items: center">
    <div class = "container" style="width: 800px; height: auto; display: flex; justify-content: center;">
        <div style="width: 100%; border-width: 1px; border-style: solid; height: fit-content;">
            
            <center>
                <div style="width: 100%;border-top: 0px;border-left: 0px;border-bottom: 1px;border-right: 0px;border-style: solid;height: 50px; margin-top: 10px;">
                    <img class = "logo" style="width: 300; height: 40;" src="http://4castbet.com/bet_community/Public/images/logo1.png" alt="logo">
                </div>
            </center>

            <!--Body content-->
            <div style = "padding: 10px; font-family: verdana">
                <p style = "background-color: #0089C9; color: white; margin: 0px !important; padding: 5px; border-radius: 5px; font-weight: bolder; font-size: 15;">Match Result</p>
                <hr style = "margin: 0px; border-color: rgba(4, 146, 212, 0.55); border-width: 1px; border-top: 0px;"/>

                <!--post description-->
                <ul style = "display:flex; margin-top: 10px; padding: 0px; font-size: 12px; list-style-type:none">
                    <div style = "padding-right: 10px">
                        <li>
                            <strong style = "color:grey">Post Date: </strong>
                        </li>
                        <li>
                            <strong style = "color:grey">Book Maker: </strong>
                        </li>
                        <li>
                            <strong style = "color:grey">Slip No: </strong>
                        </li>
                        <li><strong style = "color:grey">Game Position : </strong></li>
                    </div>
                    <div>
                        <li><span>' . $dateCreated . '</span></li>
                        <li>
                            <span>' . $type . '</span>
                        </li>
                        <li>
                            <span>' . $betslip . '</span>
                       </li>
                        <li>
                            <span>' . $gamePos . '</span>
                        </li>
                    </div>
                </ul>
                <!-- end of post description-->

                <div style = "margin-top: 10px; font-size: 12px">

                    <!--Each competition group-->
                    <table style = "border-collapse: collapse; margin-top: 10px; overflow: hidden; width: 100%; font-size: 12px; font-weight: normal;">
                        <thead>
                            <tr >
                                <td colspan = "4">
                                    <span style = "width:100%; display: block; background: #DBD6D6; padding: 3px 0px">'
                                      . $match->competition_name .
                                    '</span>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <!--event head-->
                            <tr style = "text-align: center">
                                <td colspan = "2">
                                   
                                </td>
                                <td>
                                   <span style = "background: #0089C9; color: white; padding: 3px 10px; border-radius: 5px;">Ht"</span>
                                </td>
                                <td>
                                    <span style = "background: #0089C9; color: white; padding: 3px 10px; border-radius: 5px;">Ft"</span>
                                </td>
                            </tr>
                            <!--end of event head-->
                            
                            <!--each events-->
                            <div>
                                <tr>
                                    <!--additional informatmion-->
                                    <td style = "border-bottom: 1px solid #ddd; font-size: 10px; color: grey">
                                        <div>12:00 am</div>
                                        <div style = "margin-top: 5px">
                                            <span>Status: </span>
                                            <span style = "background: green; width: 10px; height: 10px;display: inline-block; border-radius: 50%;
                                            box-shadow: 0px 0px 3px green"></span>
                                        </div>
                                    </td>
                                    <!--end of additional information-->
                                    <td style = "border-bottom: 1px solid #ddd;  width: 50%">
                                        <div style = "width: 100%; height: 100%">
                                            <div style = "font-weight: bolder">' . $match->home_name . '</div>
                                            <div style = "font-weight: bolder">' . $match->away_name . '</div>
                                        </div>
                                    </td>
                                    <td style = "border-bottom: 1px solid #ddd; text-align: center">
                                        <div style = "color: #0089C9; width: 100%; height: 100%">
                                            <div>' . $htScore[0] . '</div>
                                            <div>' . $htScore[1] . '</div>
                                        </div>
                                    </td>
                                    <td style = "border-bottom: 1px solid #ddd; text-align: center">
                                        <div style = "color: #0089C9; width: 100%; height: 100%">
                                            <div>' . $ftScore[0] . '</div>
                                            <div>' . $ftScore[1] . '</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                            <!--end of each event-->
                <!--button container-->
                <center>
                <div style = "width: 100%; height: auto; margin-top: 20px;">
                    <!--button-->
                    <button style = "box-shadow:inset 0px 1px 0px 0px #97c4fe; background:linear-gradient(to bottom, #3d94f6 5%, #1e62d0 100%); background-color:#3d94f6; border-radius:6px; border:1px solid #337fed;
                    display:inline-block; cursor:pointer; color:#ffffff; font-family:Arial; font-size:15px; font-weight:bold; padding:6px 24px; text-decoration:none; text-shadow:0px 1px 0px #1570cd;"
                    onmouseover = "this.style.background = "linear-gradient(to bottom, #1e62d0 5%, #3d94f6 100%)"; this.style.background = "#1e62d0""
                    onmousedown="this.style.position = "relative"; this.style.top =  1+"px""
                    onmouseup="this.style.position = "relative"; this.style.top =  0+"px"">
                        Click to view post status
                    </button>
                    <!--end of button-->
                </div>
                </center>

                <!--end of button container-->
                <!--Pings-->
                <div style = "font-size: 12px; color: grey; justify-content: center; align-items: center; width: 100%;">
                    <div style = "margin-top: 5px;">
                        <span style = "background: green; width: 10px; height: 10px;display: inline-block; border-radius: 50%; box-shadow: 0px 0px 3px green"></span>
                        <span>Won</span>
                    </div>
                    <div style = "margin-top: 5px;">
                        <span style = "background: red; width: 10px; height: 10px;display: inline-block; border-radius: 50%; box-shadow: 0px 0px 3px red"></span>
                        <span>Lost</span>
                    </div>
                    <div style = "margin-top: 5px;">
                        <span style = "background: orange; width: 10px; height: 10px;display: inline-block; border-radius: 50%; box-shadow: 0px 0px 3px orange"></span>
                        <span>Cancelled</span>
                    </div>
                    
                </div>
            </div>
            <!--end of body content-->
            
            <!--footer content-->
            <center>

            <div style="font-size: 12px; flex-direction: column; margin-top: 100px; height: 50px;">
                <div style="color: #27aae1;font-weight:­ 700;">
                    Contact Us
                </div>
                <div style="display: flex; justify-content: center">
                    <div style="color: black; font-weight: 700; width: 50%; text-align: right;">
                    +1 (234) 222 0754
                    </div>
                    <div style="color: black;font-weight: 700;margin-left: 10px; width: 50%; text-align: left;">
                        info@4castbet.com
                    </div>
                </div>
            </div>
            </center>
            <div style="font-size: 12px; background: #231F20;text-align: center;color: #fff;top: 457;width: 100%; height: 45px; display: flex; justify-content: center;">
                <p style="text-align: center; width: 100%;">4castBet © 2020. All rights reserved</p>
            </div>
            <!-- end of footer content-->
        </div>
    </div>
    </div>';

    $e = new SendMail($email, 'Your 4CastBet Single game result prediction', $emailBody, true);
    $e->send();
    // return $emailBody;
}

function genererateNotificationEmailHtmlForAll($predictionObj, $dateCreated, $type, $email) {
    // exit;
    $emailBody = '<head>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <style>
        @media screen and (max-width: 420px){
            .container{
                width: 100% !important;
            }
        }
        
        @media screen and (min-width: 420px){
            .container{
                width: 420px !important;
            }
        }
        @media screen and (max-width: 352px){
            .logo{
                width: 80%;
                height: auto
            }
        }
        tr:nth-child(even){
            background: #f2f2f2;
        }
    </style>
</head>
<!-- <meta http-equiv = "refresh" content = "1"> -->
<div style = "width: 100%; height: fit-content; display: flex; justify-content: center; align-items: center">
    <div class = "container" style="width: 800px; height: auto; display: flex; justify-content: center;">
        <div style="width: 100%; border-width: 1px; border-style: solid; height: fit-content;">
            
            <center>
                <div style="width: 100%;border-top: 0px;border-left: 0px;border-bottom: 1px;border-right: 0px;border-style: solid;height: 50px; margin-top: 10px;">
                    <img class = "logo" style="width: 300; height: 40;" src="http://4castbet.com/bet_community/Public/images/logo1.png" alt="logo">
                </div>
            </center>

            <!--Body content-->
            <div style = "padding: 10px; font-family: verdana">
                <p style = "background-color: #0089C9; color: white; margin: 0px !important; padding: 5px; border-radius: 5px; font-weight: bolder; font-size: 15;">Match Result</p>
                <hr style = "margin: 0px; border-color: rgba(4, 146, 212, 0.55); border-width: 1px; border-top: 0px;"/>

                <!--post description-->
                <ul style = "display:flex; margin-top: 10px; padding: 0px; font-size: 12px; list-style-type:none">
                    <div style = "padding-right: 10px">
                        <li>
                            <strong style = "color:grey">Post Date: </strong>
                        </li>
                        <li>
                            <strong style = "color:grey">Book Maker: </strong>
                        </li>
                        <li>
                            <strong style = "color:grey">Slip No: </strong>
                        </li>
                        <li><strong style = "color:grey">No. Selection : </strong></li>
                    </div>
                    <div>
                        <li><span>' . $dateCreated . '</span></li>
                        <li>
                            <span>' . $type . '</span>
                        </li>
                        <li>
                            <span>' . $predictionObj->bet_code . '</span>
                        </li>
                        <li>
                            <span>' . sizeof($predictionObj->leagues) . '</span>
                        </li>
                    </div>
                </ul>
                <!-- end of post description-->

                <div style = "margin-top: 10px; font-size: 12px">'

                   .  generateEachGroupHtml(groupPrediction($predictionObj), $predictionObj->scores) .

                '</div>
                <!--end of each event-->

                <!--button container-->
                <center>
                <div style = "width: 100%; height: auto; margin-top: 20px;">
                    <!--button-->
                    <button style = "box-shadow:inset 0px 1px 0px 0px #97c4fe; background:linear-gradient(to bottom, #3d94f6 5%, #1e62d0 100%); background-color:#3d94f6; border-radius:6px; border:1px solid #337fed;
                    display:inline-block; cursor:pointer; color:#ffffff; font-family:Arial; font-size:15px; font-weight:bold; padding:6px 24px; text-decoration:none; text-shadow:0px 1px 0px #1570cd;"
                    onmouseover = "this.style.background = "linear-gradient(to bottom, #1e62d0 5%, #3d94f6 100%)"; this.style.background = "#1e62d0""
                    onmousedown="this.style.position = "relative"; this.style.top =  1+"px""
                    onmouseup="this.style.position = "relative"; this.style.top =  0+"px"">
                        Click to view post status
                    </button>
                    <!--end of button-->
                </div>
                </center>

                <!--end of button container-->
                <!--Pings-->
                <div style = "font-size: 12px; color: grey; justify-content: center; align-items: center; width: 100%;">
                    <div style = "margin-top: 5px;">
                        <span style = "background: green; width: 10px; height: 10px;display: inline-block; border-radius: 50%; box-shadow: 0px 0px 3px green"></span>
                        <span>Won</span>
                    </div>
                    <div style = "margin-top: 5px;">
                        <span style = "background: red; width: 10px; height: 10px;display: inline-block; border-radius: 50%; box-shadow: 0px 0px 3px red"></span>
                        <span>Lost</span>
                    </div>
                    <div style = "margin-top: 5px;">
                        <span style = "background: orange; width: 10px; height: 10px;display: inline-block; border-radius: 50%; box-shadow: 0px 0px 3px orange"></span>
                        <span>Cancelled</span>
                    </div>
                    
                </div>
            </div>
            <!--end of body content-->
            
            <!--footer content-->
            <center>

            <div style="font-size: 12px; flex-direction: column; margin-top: 100px; height: 50px;">
                <div style="color: #27aae1;font-weight:­ 700;">
                    Contact Us
                </div>
                <div style="display: flex; justify-content: center">
                    <div style="color: black; font-weight: 700; width: 50%; text-align: right;">
                    +1 (234) 222 0754
                    </div>
                    <div style="color: black;font-weight: 700;margin-left: 10px; width: 50%; text-align: left;">
                        info@4castbet.com
                    </div>
                </div>
            </div>
            </center>
            <div style="font-size: 12px; background: #231F20;text-align: center;color: #fff;top: 457;width: 100%; height: 45px; display: flex; justify-content: center;">
                <p style="text-align: center; width: 100%;">4castBet © 2020. All rights reserved</p>
            </div>
            <!-- end of footer content-->
        </div>
    </div>
    </div>';
    $e = new SendMail($email, 'Your 4CastBet Prediction Results', $emailBody, true);
    $e->send();
}

function getScore($scores, $fixture) {
    //var_dump($feature); exit;
    foreach($scores as $score) {
        // echo $feature; var_dump($score); exit;
        if (property_exists($score, $fixture)) {
            return $score->$fixture;
        }
    }
}


function generateEachGroupHtml($groupedPrediction, $scores) {
    $html = '';
    foreach($groupedPrediction as $prop => $obj) {
        $dates = $obj->dates;
        $fixtures = $obj->fixtures;
        $html .= '<table style = "border-collapse: collapse; margin-top: 10px; overflow: hidden; width: 100%; font-size: 12px; font-weight: normal;">
        <thead>
            <tr >
                <td colspan = "4">
                    <span style = "width:100%; display: block; background: #DBD6D6; padding: 3px 0px">'
                      . $prop .
                    '</span>
                </td>
            </tr>
        </thead>
        <tbody>
            <!--event head-->
            <tr style = "text-align: center">
                <td colspan = "2">
                   
                </td>
                <td>
                   <span style = "background: #0089C9; color: white; padding: 3px 10px; border-radius: 5px;">Ht"</span>
                </td>
                <td>
                    <span style = "background: #0089C9; color: white; padding: 3px 10px; border-radius: 5px;">Ft"</span>
                </td>
            </tr>
            <!--end of event head-->
            
            <!--each events-->
            <div>'  
                . getGames($dates, $fixtures, $scores) .
            '</div>

           </tbody>
        </table>';
    }

    return $html;
}

function getGames($dates, $fixtures, $scores) {
    $html = '';
    for($i = 0; $i < sizeof($fixtures); $i++) {
        $fixture = $fixtures[$i];

        $score = getScore($scores, $fixture);
        $homeAwayScore = explode('-', str_replace(' ', '', $score));
        $homeAway = explode(' - ', $fixture);
        $time = explode(' ', $dates[$i])[1];

        $html .= '<tr>
            <!--additional informatmion-->
            <td style = "border-bottom: 1px solid #ddd; font-size: 10px; color: grey">
                <div>' . $time . '</div>
                <div style = "margin-top: 5px">
                    <span>Status: </span>
                    <span style = "background: green; width: 10px; height: 10px;display: inline-block; border-radius: 50%;
                    box-shadow: 0px 0px 3px green"></span>
                </div>
            </td>
            <!--end of additional information-->
            <td style = "border-bottom: 1px solid #ddd;  width: 50%">
                <div style = "width: 100%; height: 100%">
                    <div style = "font-weight: bolder">' . $homeAway[0] . '</div>
                    <div style = "font-weight: bolder">' . $homeAway[1]. '</div>
                </div>
            </td>
            <td style = "border-bottom: 1px solid #ddd; text-align: center">
                <div style = "color: #0089C9; width: 100%; height: 100%">
                    <div>' . $homeAwayScore[0] . '</div>
                    <div>' . $homeAwayScore[1] . '</div>
                </div>
            </td>
            <td style = "border-bottom: 1px solid #ddd; text-align: center">
                <div style = "color: #0089C9; width: 100%; height: 100%">
                    <div>' . $homeAwayScore[0]  . '</div>
                    <div>' . $homeAwayScore[1] . '</div>
                </div>
            </td>
        </tr>';
    }

    return $html;
}

function getProp($str) {
    return $str;
}

function groupPrediction($predictionObj) {
    $groupedPrediction = (object)array();
    var_dump($predictionObj->leagues);

    for ($i = 0; $i < sizeof($predictionObj->leagues); $i++) {
        $prop = getProp($predictionObj->leagues[$i]);
        if (property_exists($groupedPrediction, $prop)) {
            array_push($groupedPrediction->$prop->fixtures, $predictionObj->fixtures[$i]);
            array_push($groupedPrediction->$prop->dates, $predictionObj->dates[$i]);
        } else {
            $groupedPrediction->$prop = (object)array(
                'fixtures' => [$predictionObj->fixtures[$i]],
                'dates' => [$predictionObj->dates[$i]],
            );
          //  var_dump($groupedPrediction->$prop);
        }
    }

    return $groupedPrediction;
}

$match = (object)[
    "competition_id"=> 370,
    "status"=> "IN PLAY",
    "ht_score"=> "0 - 0",
    "ft_score"=> "0 - 2",
    "et_score"=> "",
    "last_changed"=> "2019-07-19 14=>14=>05",
    "id"=> 149525,
    "league_name"=> "Club Friendlies",
    "away_id"=> 499,
    "score"=> "0 - 2",
    "competition_name"=> "Club Friendlies",
    "events"=> false,
    "home_id"=> 0,
    "away_name"=> "Bristol City",
    "added"=> "2019-07-19 12=>45=>04",
    "time"=> "37",
    "home_name"=> "Sarasota Metropolis FC",
    "league_id"=> 5,
    "location"=> "IMG Academy Bradenton, Florida",
    "fixture_id"=> 5,
    "scheduled"=> "14:00"
];
// $str = '{"leagues":["Premier League","Bundesliga","Allsvenskan","Allsvenskan","Bundesliga","Premier League","Serie A"],"fixtures":["FC Ararat-Armenia - Alashkert","TSV Hartberg - Wolfsberger AC","Hammarby IF - Varbergs BoIS FC","Orebro SK - BK Hacken","Werder Bremen - 1. FC Heidenheim 1846","Man City - Liverpool","Roma - Udinese"],"outcomes":["1X2 - Home","1X2 - Away","1X2 - Home","1X2 - Away","1X2 - Home","1X2 - Away","1X2 - Home"],"odds":["2.00","1.48","1.53","2.00","1.54","3.84","1.78"],"dates":["2020-07-02 16:00","2020-07-02 16:30","2020-07-02 17:00","2020-07-02 17:00","2020-07-02 18:30","2020-07-02 19:15","2020-07-02 19:45"],"bet_code":"BC5MSRQF","scores":[{"Hammarby IF - Varbergs BoIS FC":"1 - 0"},{"Man City - Liverpool":"4 - 0"},{"Roma - Udinese":"0 - 2"},{"FC Ararat-Armenia - Alashkert":"1-0"},{"TSV Hartberg - Wolfsberger AC":"2-1"},{"FC Ararat-Armenia - Alashkert":"1-0"},{"TSV Hartberg - Wolfsberger AC":"2-1"}]}';

// $predictionObj = json_decode($str);
// // var_dump($predictionObj); exit;

// genererateNotificationEmailHtmlForAll($predictionObj, 'Bet9ja', 'FGHRTY6', 'pythonboss123@gmail.com');
?>


?>