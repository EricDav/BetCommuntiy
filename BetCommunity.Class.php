<?php

class BetCommunity {
    const IMAGES_PATH = "/bet_community/Public/images/users/";
    const DEFAULT_ADD_PROFILE = 180191;
    const DEFAULT_METHOD = -1;
    const NUM_PREDICTIONS_PER_DAY = 3;
    const routes = [
        '/login' => "LoginController",
        '/api/web/create-prediction' => "CreatePredictionController",
        '/api/web/users-action' => "UserController",
        '/api/web/bet-games' => "BetGamesController",
        '/api/web/fixtures' => "FixturesController",
        '/api/web/results' => "ResultsController",
        '/users/profile' => "UserProfileController",
        '/api/web/update-profile' => "UpdateUserProfileController",
        '/contact' => "ContactController",
        '/logout' => 'UserController@logout',
        '/api/web/delete-prediction' => "PredictionController@deletePrediction",
        '/forcasters' => "UserController@getForcasters",
        '/api/web/report-prediction' => "PredictionController@reportPrediction",
        '/predictions' => "PredictionController@getPrediction",
        '/forgot-password' => "ForgotPasswordController",
        '/forgot-password/reset' => "ForgotPasswordController@resetPassword",
        '/benefits' => "BenefitsController",
        '/api/web/notifications' => "NotificationController",
        '/api/web/notifications/clear-seen' => "NotificationController@clearSeen",
        '/api/web/predictions/like' => "PredictionController@like",
        '/api/web/predictions/determine-outcome' => "PredictionController@updateWonStatus",
        '/predictions/pending-outcomes' => "HomeController",
        '/predictions/my/approved-outcomes' => "HomeController",
        '/api/web/notifications/email-settings' => "NotificationController@updateSettings",
        '/notifications/email-settings' => "NotificationController@updateEmailSettings",
        '/api/web/predictions/update' => 'PredictionController@update',
        '/about' => "AboutController",
        '/' => "HomeController"
    ];

    const BUGS = [
        'Invalid booking number',
        'Some results are not correct',
        'Failed Prediction marks as success',
        'Success prediction marks as failure',
        'Invalid outcome',
        'Other'
    ];

    // saves class name as key and path as value
    const loads = [
        'Enviroment' => 'Enviroment/Enviroment.php',
        'Request' => 'Request.php',
        'Controller' => 'Controllers/Controller.php',
        'LoginController' => 'Controllers/Login.Controller.php',
        'Validation' => 'Helper/Validation.php',
        'UserModel' => 'Models/User.Model.php',
        'DBConfig' => 'Config/Config.php',
        'PDOConnection' => 'DB/DBConnection.php',
        'CreatePredictionController' => 'Controllers/CreatePrediction.Controller.php',
        'PredictionModel' => 'Models/Prediction.Model.php',
        'HomeController' => 'Controllers/Home.Controller.php',
        'UserController' => 'Controllers/User.Controller.php',
        'BetGamesController' => 'Controllers/BetGames.Controller.php',
        'FixturesController' => 'Controllers/Fixtures.Controller.php',
        'ResultsController' => 'Controllers/Results.Controller.php',
        'UserProfileController' => 'Controllers/UserProfile.Controller.php',
        'UpdateUserProfileController' => 'Controllers/UpdateUserProfile.Controller.php',
        'ContactController' => 'Controllers/Contact.Controller.php',
        'ContactModel' => 'Models/Contact.Model.php',
        'PredictionController' => 'Controllers/Prediction.Controller.php',
        'ForgotPasswordController' => 'Controllers/ForgotPassword.Controller.php',
        'ForgotPasswordModel' => 'Models/ForgotPassword.Model.php',
        'SendMail' => 'SendMail.php',
        'BenefitsController' => 'Controllers/Benefits.Controller.php',
        'NotificationModel' => 'Models/Notification.Model.php',
        'FollowerModel' => 'Models/Follower.Model.php',
        'NotificationController' => 'Controllers/Notification.Controller.php',
        'AboutController' => 'Controllers/About.Controller.php',
        'ErrorMail' => 'ErrorMail.php',
    ];

    const OUTCOMES = array('1', '2', 'X', '1X', 'X2', '12', 'GG', 'NG', 'Over 0.5', 'Over 1.5',
        'Over 2.5', 'Over 3.5', 'Over 4.5', 'Over 5.5', 'Under 0.5', 'Under 1.5', 'Under 2.5',
        'Under 3.5', 'Under 4.5', 'Under 5.5', '1-0) 1 Handicap', '(2:0) 1 Handicap',
        '1 Handicap', '(0:2) 1 Handicap', '(1:0) 2 Handicap', '(2:0) 2 Handicap', '(0:1) 2 Handicap',
        '(0:2) 2 Handicap', '1/X', '1/2', '2/2', '2/1', '1/1', '2/X', 'X/2', 'X/1', 'X/X', 'Over 0.5 HT',
        '1 HT', 'GG HT', 'Over 1.5 2HT', 'GG 2HT', 'Home Win Either Half (Yes)', 'Home Win Either Half (No)',
        'Away Win Either Half (Yes)', 'Away Win Either Half (No)', 'Home Scores Either Half (Yes)',
        'Home Scores Either Half (No)', 'Away Scores Either Half (Yes)', 'Away Scores Either Half (No)',
        'Home Win Both Half (Yes)', 'Home Win Both Half (No)', 'Away Win Both Half (Yes)', 'Away Win Both Half (No)',
        '1 & Over 1.5', 'X & Over 2.5', '2 & Under 3.5', '2 & Over 4.5', 'Correct Score 0-0', 'Correct Score 1-0',
        'Correct Score 1-1', 'Correct Score 2-0', 'Correct Score 2-1', 'Correct Score 2-2', 'Correct Score 3-0',
        'Correct Score 3-1', 'Correct Score 3-2', 'Correct Score 3-3', 'Correct Score 4-0,', 'Correct Score 4-1',
        'Correct Score 4-2', 'Correct Score 4-3', 'Correct Score 4-4', 'Correct Score 5-0', 'Correct Score 5-1',
        'Correct Score 5-2', 'Correct Score 5-3', 'Correct Score 6-0', 'Correct Score 6-1', 'Correct Score 6-2',
        'Correct Score 7-0', 'Correct Score 7-1');

    const countries = array
        (
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua And Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia And Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, Democratic Republic',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island & Mcdonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic Of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle Of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States Of',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts And Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre And Miquelon',
        'VC' => 'Saint Vincent And Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome And Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia And Sandwich Isl.',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard And Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad And Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks And Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.S.',
        'WF' => 'Wallis And Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    );
    
}

?>
