var startDate = $('#start-date');
var startTime = $('#start-time');
var endDate = $('#end-date');
var endTime = $('#end-time');
var prediction = $('#prediction');
var predictionButton = $('#prediction-submit');
var totalOdds = $('#total-odds');
var monthsArr = ['Jan', 'Feb', 'March', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
var inputsArr = [startDate, startTime, endDate, endTime, prediction, totalOdds];
var shouldSubmitPrediction = false; // A variable that determines if the prediction button should submit prediction to server
var predictionRes = {};

/**
 * Start of declarations of constants uses
 */
const DOT_MENU = 1 // It shows that an action is coming from the dot menu
const FOLLOW_ELEMENT = 0 // It show that an action is coming from the `follow` string 
const FOLLOW_ELEMENT_ID_PREFIX = 'follow-';
const DOT_MENU_ELEMENT_ID_PREFIX = 'dot-menu-';
const ACTION_MENU_ELEMENT_ID_PREFIX = 'action-menu-';
const ACTION_MENU_UPDATE_PREDICTION = 'update-prediction-menu-';
const USER_ICON = '<i class="fa fa-user"></i>';
const LIKE_ICON = '<i class="icon ion-thumbsup"></i>';
const USER_CANCEL_ICON = '<i class="fa fa-user-times"></i>';
const COPY_PREDICTION_LINK_PREFIX = 'copy-prediction-';
const REPORT_PREDICTION_PREFIX = 'report-prediction-';
const LIKE_MENU_ELEMENT_ID_PREFIX = 'like-'
const BOOKING_NUMBER_TAB = 1;
const FIXTURES_TAB = 2;
const TEXT_TAB = 3;
const INITIAL_EVENT_TARGET = 'h$w$PC$cCoupon$lnkLoadPrenotazione';
const FINAL_EVENT_TARGET = 'h$w$PC$cCoupon$lnkOkPrenotatore';
const PLATFORM_BET9JA = 'Bet9ja';
const PLATFORM_BETKING = 'BetKing';
const PLATFORM_SPORTY_BET = 'SportyBet';
const DEFAULT_DATE_TIME = '1900-01-01 00:00:00';
let REPORT_PREDICTION_ID = null;
let lastcreatedPreditionId = null;
let selectedOutcomePrediction = null
let predictionIdToUpdate = null;
var isMobile = false;

var currentTab = BOOKING_NUMBER_TAB // Set the default tab to the booking number tab

var tabs = [BOOKING_NUMBER_TAB, TEXT_TAB, FIXTURES_TAB];
var tabIds = ['#tab-one-id', '#tab-two-id', 'tab-three-id'];

$bookingCodetab = $('#booking-code-tab');
$tablePredictionUpdateSection =  $('#table-prediction-update');
$('#open-prediction-modal').click(function () {
    if ($$id == -1) {
        $('#login-modal-txt').html('You need to <a href="/login" style="cursor: pointer;">login or signup</a> to create a prediction');
        $('#follow-note').text('');
    }
});


if ($(this).width() <= 992) {
    var isMobile = true;
}

/**
 * This is the on click for the modal that
 * cancel icon that displays when users are 
 * not logged in. E.g when a user wants to predict 
 * and he/she isn't logged in the modal that shows up
 * this is the onclick for closing the modal.
 */
$('#close-icon').click(function () {
    $('#createPredictionModal').modal('hide');
});

$('#outcome-close-icon').click(function() {
    $('#predictionOutcomeModal').modal('hide');
});

$('#outcome-concluded-close-icon').click(function() {
    $('#concludedOutcomeModal').modal('hide');
});

/**
 * This is the on click for the modal that
 * cancel icon that displays when a prediction  
 * has just been created
 */
$('#close-icon-confirm-prediction').click(function () {
    $('#confirmModalCreate').modal('hide');
});

/**
 * Register onclick for the tabs. 
 * It set the current tab
 */
$('#tab-one-id').click(function () {
    if (currentTab == FIXTURES_TAB) {
        $('#fixtures-tab').hide();
    }

    $bookingCodetab.show();

    currentTab = BOOKING_NUMBER_TAB;

    if (Object.keys(predictionRes) > 0) {
        predictionButton.text('Submit Prediction');
    } else {
        predictionButton.text('Submit');
    }
});

$('#tab-two-id').click(function () {
    if (currentTab == BOOKING_NUMBER_TAB) {
        $bookingCodetab.hide();
    }

    $('#fixtures-tab').show();
    currentTab = FIXTURES_TAB;
    predictionButton.text('Submit');
});

$('#tab-three-id').click(function () {
    if (currentTab == BOOKING_NUMBER_TAB) {
        $bookingCodetab.hide();
    }

    if (currentTab == FIXTURES_TAB) {
        $('#fixtures-tab').hide();
    }
    currentTab = TEXT_TAB;

    predictionButton.text('Submit');
});

/**
 * Handles onclick for copying the link of the
 * just created prediction
 */
$('#copy-link-after-prediction-create').click(function () {
    console.log(lastcreatedPreditionId, '====>>>>');
    copyToClipboard(lastcreatedPreditionId);
    $('.copied-txt').text('Link copied to clipboard!');
});

/**
 * Handles onclick for viewing the just created
 * prediction
 */
$('#view-link-after-prediction-create').click(function () {
    window.location.href = window.location.origin + '/predictions?id=' + lastcreatedPreditionId;
});



/**
 * It displays the date when each prediction
 * is made. 
 */
dates.forEach(function (item, index) {
    date = new Date(item.date_created.replace(/-/g, '/') + ' UTC');
    $('#date-' + item.id.toString()).text(formatDateCreated(date));
});

var formatedPredictions = [];
var scores;

/**
 * It displays prediction data
 */
__predictionInfo.forEach(function (item, index) {
    var prediction = JSON.parse(item.prediction);
    item.prediction = prediction;
    scores = prediction.hasOwnProperty('scores') ? prediction.scores : [];
    outcomeResults = prediction.hasOwnProperty('outcome_results') ? prediction.outcome_results : [];
    formatedPredictions.push(formatPredictionDataForMobile(prediction));
    $('#prediction-info-' + item.prediction_id).append(generatePredictionInfoHtml(prediction, item.prediction_type));

    if (isMobile) {
        $('#prediction-' + item.prediction_id).append(generatePredictionHtmlForMobileView(formatPredictionDataForMobile(prediction), scores, outcomeResults));
    } else {
        $('#prediction-' + item.prediction_id).append(generatePredictionTable(prediction));
    }
});

function generatePredictionHtmlForMobileView(prediction, scores, outcomeResults) {
    console.log(prediction, '========>>>>>>>');
    $html = '<div class="mobile-prediction-wrapper">';

    var homeScore;
    var awayScore;
    var resultArr
    for (league in prediction) {
        $html += '<div class="mobile-league">' + league + '</div>';
        datesFixturesObj = prediction[league];

        datesFixturesObj.dates.forEach(function (date, index) {
            $html += '<div class="mobile-fixture-wrapper">';
            var dateStr = date + ' UTC';
            var date = new Date(dateStr.replace(/-/g, '/'));
            result = getScore(datesFixturesObj.fixtures[index], scores);
            outcomeResult = getOutcomeResult(datesFixturesObj.fixtures[index], outcomeResults);

            if (result) {
                resultArr = result.split(' - ');
                homeScore = '<span style="color: #27aae1; margin-left: 5px;">' + resultArr[0] + '</span>';
                awayScore = '<span style="color: #27aae1; margin-right: 5px;">' + resultArr[1] + '</span>';
            } else {
                homeScore = '';
                awayScore = '';
            }
            fixtureArr = datesFixturesObj.fixtures[index].split(' - ');

            $html += '<div style="font-weight: unset; max-width: 30%;">' + formatDateForPrediction(date) + '</div>';
            $html += '<div class="mobile-fixture">' + fixtureArr[0] + homeScore.toString() + ' - ' + awayScore.toString() + fixtureArr[1] + '</div>';
            $html += '<div style="font-weight: 700;">' + datesFixturesObj.outcomes[index] + outcomeResult +'</div>';
            $html += '</div>';
        });
    }

    $html += '</div>';

    return $html;
}

console.log(formatedPredictions);

console.log(__predictionInfo);

function calculateOdds(odds) {
    console.log(odds);
    resultOdds = 1;

    odds.forEach(function (odd) {
        resultOdds *= Number.parseFloat(odd);
    });

    return resultOdds.toFixed(2);
}

function generatePredictionInfoHtml(prediction, predictionType) {
    console.log(predictionType);
    $html = '<div>No.Selection:<span><b>' + prediction.leagues.length.toString() + '</b></span></div>';
    if (prediction.bet_code) {
        $html += '<div>Selection Type:<span><b>' + predictionType + '</b></span></div>';
        $html += '<div>Booking Code:<span><b>' + prediction.bet_code + '</b></span></div>';
        $html += '<div>Total Odds:<span><b>' + calculateOdds(prediction.odds) + '</b></span></div>';
    }
    return $html;
}

function generatePredictionTable(data) {
    $table = '<table style="width:100%; margin-top: 10px; border:unset;">' +
        '<tr style="border:unset;"><th style="border:unset;">Date/Time</th><th style="border:unset;">League</th><th style="border:unset;">Fixture</th><th style="border:unset;">Tip</th>' +
        (data.hasOwnProperty('scores') && data.scores.length > 0 ? '<th style="border:unset;">Result</th>' : '') + '</tr>';

    data.leagues.forEach(function (item, index) {
        if (data.dates) {
            var dateStr = data.dates[index] + ' UTC';
            var date = new Date(dateStr.replace(/-/g, '/'));
        }
        let outcomeResults = data.hasOwnProperty('outcome_results') ? data.outcome_results : [];

        $table += '<tr style="border:unset;">' + '<td style="border:unset;">' + (data.dates ? formatDateCreated(date) : 'NS') + '</td>' + '<td style="border:unset;">' + item + '</td>' + '<td style="border:unset;">' + data.fixtures[index] + '</td>' +
            '<td style="border:unset;">' + data.outcomes[index] + '</td>' + (data.hasOwnProperty('scores') && data.scores.length > 0 ? '<td style="border:unset; padding-right: 0px;">' + getScore(data.fixtures[index], data.scores) + getOutcomeResult(data.fixtures[index], outcomeResults) + '</td>' : '');
    });

    $table += '</table>';
    return $table;
}

function formatPredictionDataForMobile(prediction) {
    formatedResult = {}
    prediction.leagues.forEach(function (league, index) {
        if (league in formatedResult) {
            formatedResult[league].dates.push(prediction.dates[index]);
            formatedResult[league].fixtures.push(prediction.fixtures[index]);
            formatedResult[league].outcomes.push(prediction.outcomes[index]);
        } else {
            formatedResult[league] = { dates: [prediction.dates[index]], fixtures: [prediction.fixtures[index]], outcomes: [prediction.outcomes[index]] }
        }
    });

    return formatedResult;
}

function getOutcomeResult(fixture, outcomeResults) {
    for (let i = 0; i < outcomeResults.length; i++) {
        if (outcomeResults[i].hasOwnProperty(fixture)) {
            if (outcomeResults[i][fixture] == 1) {
                return '<i class="fa fa-apple" aria-hidden="true" style="margin-left: 3px;color: green;font-size: 15px;"></i>'
            } else {
                return '<i class="fa fa-apple" aria-hidden="true" style="margin-left: 3px;color: red;font-size: 15px;"></i>'
            }
        }
    }

    return '';
}

function getScore(fixture, scores) {
    // console.log(scores, fixture);
    for (let i = 0; i < scores.length; i++) {
        // console.log(scores[i]);
        // console.log(scores[i].hasOwnProperty(fixture))
        if (scores[i].hasOwnProperty(fixture)) {
            return scores[i][fixture];
        }
    }

    return '';
}

function copyToClipboard(predictionId) {
    var link = window.location.origin + '/predictions?id=' + predictionId;
    var $temp = $('<input>');
    $("html").append($temp);
    $temp.val(link).select();
    $temp.focus();
    document.execCommand("copy");
    $temp.remove();
}


function diff_minutes(dt2, dt1) {
    var diff = (dt2.getTime() - dt1.getTime()) / 1000;
    diff /= 60;
    return Math.abs(Math.round(diff));
}

function resetCreatePredictionModal() {
    if (currentTab == BOOKING_NUMBER_TAB) {
        removeBookingTable();
        $('#booking-number').val('');
    } else {
        $competitionSearch.val('');
        $searchFixtureInput.val('');
        $('#outcome').val('');
        $('#table-section').empty();
        $('#main-error').val('');
    }
}


$('#prediction-submit').click(function () {
    if (currentTab == BOOKING_NUMBER_TAB && !shouldSubmitPrediction) {
        var bookingNumber = $('#booking-number').val();
        var platform = $('#betting-type').val();

        if (platform == PLATFORM_BET9JA)
            fetchBet9jaGames(bookingNumber, INITIAL_EVENT_TARGET);

        if (platform == PLATFORM_BETKING)
            fetchBetKingGames(bookingNumber);

        if (platform == PLATFORM_SPORTY_BET)
            fetchSportyBetGames(bookingNumber);
    }

    if (shouldSubmitPrediction && currentTab == BOOKING_NUMBER_TAB) {
        savePrediction(predictionRes);
    }

    if (leagues.length > 0 && currentTab == FIXTURES_TAB) {
        var data = {
            leagues: leagues,
            fixtures: selectedFixtures,
            outcomes: outcomes,
            dates: dates,
            team_ids: teamIds,
            competition_ids: competitionIds
        };

        var endStart = getStartEndDateTime(data.dates);
        var utcArr = getDateTimeStrInUTC(new Date()).split(' ');
        var startDateTimeArr = endStart.startDateTime.split(' ');

        var startDateArr = startDateTimeArr[0].split('-');
        var startTimeArr = startDateTimeArr[1].split(':');

        var dateArr = utcArr[0].split('-');
        var timeArr = utcArr[1].split(':');

        var nowDateInUTC = new Date(Number.parseInt(dateArr[0]), Number.parseInt(dateArr[1]), Number.parseInt(dateArr[2]), Number.parseInt(timeArr[0]), Number.parseInt(timeArr[1]));
        var dateTimeInUTC = new Date(Number.parseInt(startDateArr[0]), Number.parseInt(startDateArr[1]), Number.parseInt(startDateArr[2]), Number.parseInt(startTimeArr[0]), Number.parseInt(startTimeArr[1]));


        if (nowDateInUTC >= dateTimeInUTC) {
            alert('First match has started');
            return;
        }
        savePrediction({
            prediction: JSON.stringify(data),
            start_date_time: endStart.startDateTime,
            end_date_time: endStart.endDateTime,
            type: 'fixtures',
        });
    }
});

$('#prediction-login').click(function () {
    window.location.href = '/login';
});

$('#report-bug').click(function () {
    console.log('reporting...');
    if ($('#problem').val() == '--- Select a Problem ---') {
        return;
    }

    var data = {
        prediction_id: REPORT_PREDICTION_ID,
        problem: $('#problem').val(),
        note: $('#extra-note').val()
    }

    console.log(data);

    $.ajax('/api/web/report-prediction', {
        data: data,
        type: 'POST', success: function (result) {
            console.log(result);
            if (!result.success) {
                console.log('sent')
            }
        }
    });
});

function getCurrentDateInStr() {
    let now = new Date();

    var month = now.getMonth() + 1;
    var minutes = now.getMinutes().toString();
    var hours = now.getHours().toString();

    if (minutes.length == 1) {
        minutes = '0' + minutes;
    }

    if (hours.length == 1) {
        hours = '0' + hours;
    }

    date = now.getFullYear() + '-' + month.toString() + '-' + now.getDate() +
        ' ' + hours + ':' + minutes;

    return date;
}


function formatDateCreated(date) {
    var now = new Date();

    var nowYear = now.getFullYear();
    var nowMonth = now.getMonth();
    var nowDay = now.getDate();

    var year = date.getFullYear();
    var month = date.getMonth();
    var day = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();

    if (nowYear == year && nowMonth == month && nowDay == day) {
        return 'Today at ' + format(date.getHours()) + ':' + format(date.getMinutes());
    }

    if (nowYear == year && nowMonth == month && nowDay - 1 == day) {
        return 'Yesterday at ' + format(date.getHours()) + ':' + format(date.getMinutes());
    }

    if (nowYear == year && nowMonth == month && nowDay + 1 == day) {
        return 'Tomorrow at ' + format(date.getHours()) + ':' + format(date.getMinutes());
    }

    if (year == nowYear) {
        return day.toString() + ' ' + monthsArr[month] + ' at ' + format(hours) + ':' + format(minutes);
    }

    return day.toString() + ' ' + monthsArr[month] + ' ' + year.toString() + ' at ' + format(hours) + ':' + format(minutes);
}

function formatDateForPrediction(date) {
    var now = new Date();

    var nowYear = now.getFullYear();
    var nowMonth = now.getMonth();
    var nowDay = now.getDate();

    var year = date.getFullYear();
    var month = date.getMonth();
    var day = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();

    if (nowYear == year && nowMonth == month && nowDay == day) {
        return format(date.getHours()) + ':' + format(date.getMinutes());
    }

    if (nowYear == year) {
        return format(date.getHours()) + ':' + format(date.getMinutes()) + ' ' + monthsArr[month] + ' ' + day.toString();
    }

    return format(hours) + ':' + format(minutes) + ' ' + monthsArr[month] + ' ' + day.toString() + ' ' + year.toString();
}




/**
 * This function is called when user is creating 
 * a prediction using the text method
 */
function implementTextPrediction() {
    clearError(); // Clear error messages 

    error = {}; // saves id of the p tag to display error messages
    errorArr = []; // saves id of input element so as to display error red border to show required elements

    /**
     * Checks for empty input elements and saves the 
     * element in the errorArr if empty.
     */
    inputsArr.forEach(function (item, index) {
        if (!item.val()) {
            errorArr.push(item.attr('id'));
        }
    });

    /**
     * This checks if error exist in the start date
     * and saves error messages in the @error object if they exist
     */
    if (errorArr.includes('start-date') && errorArr.includes('start-time')) {
        error['start'] = 'Date and time are required';
    } else if (errorArr.includes('start-date')) {
        error['start'] = 'Date is required';
    } else if (errorArr.includes('start-time')) {
        error['start'] = 'Time is required';
    } else {
        var startDateTimeStr = startDate.val() + ' ' + startTime.val();
        var startDateTime = new Date(startDateTimeStr);
        if (new Date() > startDateTime) {
            error['start'] = 'Date is in that past game already started';
        }
    }
    // End for saving error messages in start date  

    /**
     * This checks if error exist in the end date
     * and saves error messages in the @error object if they exist
     */
    if (errorArr.includes('end-date') && errorArr.includes('end-time')) {
        error['end'] = 'Date and time are required';
    } else if (errorArr.includes('end-date')) {
        error['end'] = 'Date is required';
    } else if (errorArr.includes('end-time')) {
        error['end'] = 'Time is required';
    } else {
        var endDateTimeStr = endDate.val() + ' ' + endTime.val();
        var endDateTime = new Date(endDateTimeStr);
        if (startDateTime > endDateTime) {
            error['end'] = 'Invalid date for last game begin. Last game should come after or the same time with first game';
        }
    }
    // End for saving error messages in end date 


    /**
     * This checks if error exist in the prediction input
     * and saves error messages in the @eerror object if they exist
     */
    if (errorArr.includes('prediction'))
        error['predict'] = 'Prediction is required';

    if (errorArr.includes('total-odds')) {
        error['odds'] = 'Total odds is required';
    } else if (!Number.parseFloat(totalOdds.val())) {
        error['odds'] = 'Total odds must be a number';
    }


    /**
     * This block of code display the error messages
     */
    for (property in error) {
        $('#' + property).text(error[property]);
    }


    /**
     * This block of code display the red border for empty input
     */
    errorArr.forEach(function (item, index) {
        $('#' + item).addClass('invalid');
    });

    if (errorArr.length == 0 && Object.keys(error).length == 0) {
        var lines = prediction.val().split('\n');
        var predictionStr = '';
        for (var i = 0; i < lines.length; i++) {
            if (lines[i].trim())
                predictionStr += lines[i] + '<br>';
        }

        // Get start date string in UTC
        var utcStartDateStr = (startDateTime.getUTCFullYear()).toString() +
            '-' + format(startDateTime.getUTCMonth() + 1) +
            '-' + format(startDateTime.getUTCDate()) +
            ' ' + format(startDateTime.getUTCHours()) +
            ':' + format(startDateTime.getUTCMinutes());

        // Get end date string in UTC
        var utcEndDateStr = (endDateTime.getUTCFullYear()).toString() +
            '-' + format(endDateTime.getUTCMonth() + 1) +
            '-' + format(endDateTime.getUTCDate()) +
            ' ' + format(endDateTime.getUTCHours()) +
            ':' + format(endDateTime.getUTCMinutes());


        data = {
            start_date_time: utcStartDateStr,
            end_date_time: utcEndDateStr,
            prediction: predictionStr,
            total_odds: totalOdds.val(),
            token: localStorage.getItem('$$token'),
            id: $$id
        }

        savePrediction(data);
    }
}

function getDateTimeStrInUTC(date) {
    var utcDate = (date.getUTCFullYear()).toString() +
        '-' + format(date.getUTCMonth() + 1) +
        '-' + format(date.getUTCDate()) +
        ' ' + format(date.getUTCHours()) +
        ':' + format(date.getUTCMinutes());

    return utcDate;
}


function savePrediction(data) {
    data.current_date = getCurrentDateInStr();
    data.token = localStorage.getItem('$$token');
    data.id = $$id;


    predictionButton.text('Submitting...');
    predictionButton.prop('disabled', true);
    $.ajax('/api/web/create-prediction', {
        data: data,
        type: 'POST', success: function (result) {
            predictionButton.text('Submit');
            predictionButton.prop('disabled', false);
            if (!result.success) {
                if (typeof result.messages == 'string') {
                    $('#main-error').text(result.messages);
                } else {
                    message = '';
                    count = Object.keys(result.messages) - 1;
                    counter = 0;
                    for (property in result.messages) {
                        if (counter != count) {
                            message += result.messages[property] + ', ';
                        } else {
                            message += result.messages[property];
                        }
                    }

                    $('#main-error').text(message);
                }

            } else {
                lastcreatedPreditionId = result.prediction_id
                $('#input-prediction-link').val(window.location.origin + '/predictions?id=' + lastcreatedPreditionId);
                resetCreatePredictionModal();
                $('#createPredictionModal').modal('hide');
                $('#confirmModalCreate').modal('show');
            }
        }
    });
}



$('.title').click(function () {
    var min = $('#min_odd');
    var max = $('#max_odd');
    error = {};
    $('#min-error-text').text('');
    $('#max-error-text').text('');

    if (!min.val()) {
        error['min'] = 'required';
    } else if (!Number.parseFloat(min.val())) {
        error['min'] = 'Minimum odd must be a number';
    }


    if (!max.val())
        error['max'] = 'required';
    else if (!Number.parseFloat(max.val())) {
        error['max'] = 'Maximum odd must be a number';
    } else if (Number.parseFloat(max.val()) <= Number.parseFloat(min.val())) {
        error['max'] = 'Maximum odd must be greater than minimum odd';
    }

    if (Object.keys(error).length == 0) {
        // console.log(window.location.host + '/?m=' + min.val() + '_' + max.val());
        window.location.href = window.location.origin + '/?filter_option=' + min.val() + '_' + max.val();
        // window.location.reload();
    }

    if (error['min']) {
        min.addClass('invalid');
        $('#min-error-text').text(error['min']);
    }

    if (error['max']) {
        max.addClass('invalid');
        $('#max-error-text').text(error['max']);
    }
});

function format(d) {
    d = d.toString();
    return d.length == 2 ? d : '0' + d;
}

function clearError() {
    inputsArr.forEach(function (item, index) {
        item.removeClass('invalid');
    });
    errorMessagesIds = ['#start', '#end', '#predict', '#odds'];

    errorMessagesIds.forEach(function (item, index) {
        $(item).text('');
    });
    $('#main-error').text('');
}

/**
 * It makes an api call that will follow or unfollow
 * a user with the given user_id.
 * 
 * @param {int} userId The userid of the user 
 * the current user want to follow
 * @param {int} isFollowing determine if the current user
 * is following the author. The value is either 0 or 1
 * @param {object} The object info we want to update
 * @paramm {int} The calling type 0 for follow  and 1 for dot menu.
 * It used to determine who is calling the function
 * @param {int} index of the user. 
 * 
 * @return None
 */
function followUser(userId, isFollowing, info, whoIsCalling, callingElem, index) {
    if ($$id == -1) { // checks if user is not logged in
        var name = $('#follow-' + userId.toString() + '-' + index.toString()).attr('title')
        $('#login-modal-txt').html('You need to <a href="/login" style="cursor: pointer;">login or signup</a> to follow ' + name);
        $('#follow-note').text('Note: following a user means you get notified when they drop predictions');
        $('#createPredictionModal').modal('show');
        return;
    }
    // console.log(whoIsCalling, '========>>>>'); return; 
    var isFollowingId = isFollowing ? 1 : 0;
    data = {
        token: localStorage.getItem('$$token'),
        id: $$id,
        action_type: 'follow',
        user_id: userId,
        is_following: isFollowingId
    };

    $.ajax('/api/web/users-action', {
        data: data,
        type: 'POST', success: function (result) {
            if (result.success) {
                if (whoIsCalling == DOT_MENU) {
                    // Update all predictions by the user
                    __predictionInfo.forEach(function (info, index) {
                        if (info.user_id == userId) {
                            info.isFollowing_author = !info.isFollowing_author;
                            var text = info.isFollowing_author ? USER_CANCEL_ICON + ' Unfollow ' + info.first_name : USER_ICON + ' Follow ' + info.first_name;
                            var dotElem = $('#' + DOT_MENU_ELEMENT_ID_PREFIX + info.user_id.toString() + '-' + index.toString())
                            dotElem.text('');
                            dotElem.append(text);

                            // update follow element
                            var text = info.isFollowing_author ? 'Following' : 'Follow';
                            $('#' + FOLLOW_ELEMENT_ID_PREFIX + info.user_id.toString() + '-' + index.toString()).text(text)
                        }
                    });
                }

                if (whoIsCalling == FOLLOW_ELEMENT) {
                    // Update all predictions by the user
                    __predictionInfo.forEach(function (info, index) {
                        if (info.user_id == userId) {
                            info.isFollowing_author = !info.isFollowing_author;
                            var followingJqueryObj = $('#' + FOLLOW_ELEMENT_ID_PREFIX + info.user_id.toString() + '-' + index.toString());
                            followingJqueryObj.text('Following');
                            followingJqueryObj.css('cursor', 'default');

                            /**
                             * Update icon and text of dot menu
                             */
                            var jqueryElemObj = $('#' + DOT_MENU_ELEMENT_ID_PREFIX + info.user_id.toString() + '-' + index.toString());
                            jqueryElemObj.text('')
                            jqueryElemObj.append(USER_CANCEL_ICON + ' Unfollow ' + info.first_name);
                        }
                    });
                }
            }

            return false;
        }
    });
}

/**
 * Deleting prediction section start
 */
let DELETE_PREDICTION_ID;

$('.delete-close-icon').click(function () {
    $('#deleteModal').modal('hide');
});

$('#delete-cancel').click(function () {
    $('#deleteModal').modal('hide');
});

$('#delete-prediction').click(function () {
    deletePrediction();
});

function openDeleteConfirmationModal(predictionId) {
    console.log('INININ');
    $('#deleteModal').modal('show');
    DELETE_PREDICTION_ID = predictionId;
}

function deletePrediction() {
    $('#deleteModal').modal('show');
    data = {
        token: localStorage.getItem('$$token'),
        id: $$id,
        prediction_id: DELETE_PREDICTION_ID,
    };

    $('#delete-prediction').prop('disabled', true);
    $('#delete-prediction').text('Deleting...');
    $.ajax('/api/web/delete-prediction', {
        data: data,
        type: 'POST', success: function (result) {
            $('#delete-prediction').prop('disabled', false);
            $('#delete-prediction').text('Delete');
            if (result.success) {
                $('#prediction-box-' + DELETE_PREDICTION_ID.toString()).remove();
                $('#deleteModal').modal('hide');
            }

            return false;
        }
    });
}
/**
 * Deleting prediction section end
 */

function likePrediction(likeEvent, predictionInfo, predictionId) {
    if ($$id == -1) { // checks if user is not logged in
        $('#login-modal-txt').html('You need to <a href="/login" style="cursor: pointer;">login or signup</a> to like this prediction');
        $('#createPredictionModal').modal('show');
        return;
    }
    //  var data = { prediction_id: predictionId, token: token, id: $$id}
    $.ajax('/api/web/predictions/like', {
        data: { prediction_id: predictionId, token: token, id: $$id },
        type: 'POST', success: function (result) {
            console.log(result);
            if (result.success) {
                if (result.message == 'like') {
                    predictionInfo.num_likes = predictionInfo.num_likes + 1;
                } else {
                    predictionInfo.num_likes = predictionInfo.num_likes - 1;
                }
                var text = LIKE_ICON + ' ' + (predictionInfo.num_likes == 0 ? '' : predictionInfo.num_likes.toString());
                $(likeEvent).text('');
                $(likeEvent).html(text);
            }
        }
    });
}



/**
 * Add click events to menu follow user or unfollow, copy link and report bug
 * or to the follow link beside author name when login
 * user is not following the author.
*/
__predictionInfo.forEach(function (item, index) {
    // Copy prediction link 
    $('#' + COPY_PREDICTION_LINK_PREFIX + item.prediction_id.toString()).click(function () {
        copyToClipboard(item.prediction_id);
    });

    // Add onclick for clicking report prediction link on prediction menu
    $('#' + REPORT_PREDICTION_PREFIX + item.prediction_id.toString()).click(function () {
        REPORT_PREDICTION_ID = item.prediction_id;
    });

    // Add onclick for deleting predictions you own
    $('#' + DOT_MENU_ELEMENT_ID_PREFIX + 'delete-' + item.prediction_id.toString() + '-' + index.toString())
        .click(function () {
            console.log('Openeing modal for deleting')
            openDeleteConfirmationModal(item.prediction_id);
    });

    //  Add onclick to the like button
    $('#' + LIKE_MENU_ELEMENT_ID_PREFIX + item.prediction_id.toString() + '-' + index.toString())
        .click(function () {
            likePrediction(this, item, item.prediction_id);
    });



    // add onclick for prediction out come
    $('#' + ACTION_MENU_ELEMENT_ID_PREFIX + item.prediction_id.toString() + '-' + index.toString())
        .click(function () {
            selectedOutcomePrediction = item;
            if (item.won == null) {
                $('#predictionOutcomeModal').modal('show');
            } else {
                $('#concludedOutcomeModal').modal('show');
            }
    });


    // add onclick for prediction update
    $('#' + ACTION_MENU_UPDATE_PREDICTION + item.prediction_id.toString() + '-' + index.toString())
        .click(function () {
            predictionIdToUpdate = item.prediction_id;
            $('#updatePredictionModal').modal('show');
            updatedScores = [];
            updatedOutcomeResults = [];
            $tablePredictionUpdateSection.append(generateTableForUpdate(item.prediction, item.prediction_id));
    });


    if (!item.isFollowing_athour) {
        $('#' + FOLLOW_ELEMENT_ID_PREFIX + item.user_id.toString() + '-' + index.toString())
            .click(function () {
                if (!item.isFollowing_author) {
                    followUser(item.user_id, item.isFollowing_author, item, FOLLOW_ELEMENT, this, index);
                }
        });
    }

    $('#' + DOT_MENU_ELEMENT_ID_PREFIX + item.user_id.toString() + '-' + index.toString())
        .click(function () {
            console.log('Yeah!!!');
            followUser(item.user_id, item.isFollowing_author, item, DOT_MENU, this, index);
    });
});
var modalBodyElem = $('#m-body-id');
var isResultDisplayed = false; // If the result has been displayed on the modal it is true else false

function resetButton() {
    predictionButton.text('Submit');
    predictionButton.prop('disabled', false);
}

function fetchBet9jaGames(code, target) {
    data = {
        '__EVENTTARGET': target,
        '__VIEWSTATE': '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',
        'h$w$PC$cCoupon$txtPrenotatore': code,
        'h$w$PC$cCoupon$hidAttesa': 0,
        'h$w$PC$cCoupon$hidCouponAsincrono': 0,
        'h$w$PC$cCoupon$hidModificatoQuote': 1,
        'h$w$PC$cCoupon$hidNumItemCoupon': 0,
        'h$w$PC$cCoupon$hidPrintAsincronoDisabled': 0,
        'h$w$SM': 'h$w$PC$cCoupon$atlasCoupon|h$w$PC$cCoupon$lnkLoadPrenotazione'
    }

    if (isResultDisplayed) {
        var bookingTabLastElem = $bookingCodetab.children()[$bookingCodetab.children().length - 1]; // get the last element
        $(bookingTabLastElem).remove();
        isResultDisplayed = false;
    }

    if (!$('#booking-number').val()) {
        $bookingCodetab.append('<div class="booking-not-found">Booking code is required, it can not be empty</div>');
        isResultDisplayed = true;
        return;
    }

    predictionButton.text('Loading games...');
    predictionButton.prop('disabled', true);
    $.ajax('https://web.bet9ja.com/Sport/Default.aspx', {
        data: data,
        type: 'POST', success: function (result) {

            var leagues = [];
            var fixtures = [];
            var outcome = [];
            var odds = [];

            var lItem = $(result).find('.CEvento');
            var fItem = $(result).find('.CSubEv');
            var outcomeItem = $(result).find('.CSegno');
            var oddsR = $(result).find('.valQuota_1')
            var matchesStarted = $(result).find('#h_w_PC_cCoupon_mexPrenotazione')[0];
            var totalOdds = $(result).find('#h_w_PC_cCoupon_lblQuotaTotale').text();

            if (target == INITIAL_EVENT_TARGET && matchesStarted.innerText.search('not found') != -1) {
                $('#booking-code-tab').append('<div class="booking-not-found"> Booking code not found </div>');
                isResultDisplayed = true;
                resetButton();
                return;
            }

            if (target == INITIAL_EVENT_TARGET && matchesStarted.innerText.search('All matchs are expired ') != -1) {
                $('#booking-code-tab').append('<div class="booking-not-found">All games have expired </div>');
                isResultDisplayed = true;
                resetButton();
                return;
            }

            if (target == INITIAL_EVENT_TARGET && matchesStarted.innerText.search('All matchs are expired ') == -1 && leagues.length == 0 && target != FINAL_EVENT_TARGET) {
                return fetchBet9jaGames(code, FINAL_EVENT_TARGET);
            }

            resetButton();

            for (var i = 0; i < lItem.length; i++) {
                leagues.push(lItem[i].textContent);
            }

            for (var i = 0; i < fItem.length; i++) {
                fixtures.push(fItem[i].textContent);
            }

            for (var i = 0; i < outcomeItem.length; i++) {
                outcome.push($(outcomeItem[i]).attr('title'));
            }

            for (var i = 0; i < oddsR.length; i++) {
                odds.push(oddsR[i].textContent);
            }

            if (leagues.length == 0) {
                /**
                 * At this place something unexpected happen
                 */
                $('#booking-code-tab').append('<div class="booking-not-found">For some reasons we could not fetch games from the booking number you provided try another code. If the problem persist try another method.</div>');
                isResultDisplayed = true;
                return;
            }

            /**
             * This section represent successful retrieval 
             * of booking games.
             */
            var data = {
                leagues: leagues,
                fixtures: fixtures,
                outcomes: outcome,
                odds: odds,
                totalOdds: totalOdds,
                bet_code: code
            };

            $('#booking-code-tab').append(generateTable(data));
            predictionButton.text('Submit Prediction');
            isResultDisplayed = true;
            $('#booking-number').prop('disabled', true);
            $('#betting-type').prop('disabled', true);
            shouldSubmitPrediction = true;

            predictionRes = {
                prediction: JSON.stringify(data),
                start_date_time: DEFAULT_DATE_TIME,
                end_date_time: DEFAULT_DATE_TIME,
                type: PLATFORM_BET9JA,
            };
        }
    });
}

/**
 * This functions gets the predictions from a betking
 * booking code. It displays it if successful and submit
 * to the server.
 * 
 * @param {String} code The booking code
 */
function fetchBetKingGames(code) {
    if (isResultDisplayed) {
        // var bookingTabLastElem = $bookingCodetab.children()[$bookingCodetab.children().length-1]; 
        var bookingLastElem = $bookingCodetab.children()[$bookingCodetab.children().length - 1]; // get the last element
        $(bookingLastElem).remove();
        isResultDisplayed = false;
    };

    console.log($('#booking-number').val(), '=====>>>>>>>>>');

    if (!$('#booking-number').val()) {
        $bookingCodetab.append('<div class="booking-not-found">Booking code is required, it can not be empty</div>');
        isResultDisplayed = true;
        return;
    }

    predictionButton.text('Loading games...');
    predictionButton.prop('disabled', true);
    $.ajax('/api/web/bet-games', {
        type: 'GET', data: { code: code, type: PLATFORM_BETKING, token: localStorage.getItem('$$token'), id: $$id },
        success: function (result) {
            var leagues = [];
            var fixtures = [];
            var outcomes = [];
            var odds = [];
            var dates = [];

            predictionButton.text('Submit');
            predictionButton.prop('disabled', false);

            if (!result.data.BookedCoupon) {
                $bookingCodetab.append('<div class="booking-not-found">Invalid booking code</div>');
                isResultDisplayed = true;
                return;
            }

            var betKingData = result.data.BookedCoupon.Odds;
            var totalOdds = { maxOdd: result.data.BookedCoupon.MaxOdd, minOdd: result.data.BookedCoupon.MinOdd }

            if (!betKingData) {
                $bookingCodetab.append('<div class="booking-not-found">Invalid booking code</div>');
                isResultDisplayed = true;
                return;
            }

            if (betKingData.length == 0) {
                $bookingCodetab.append('<div class="booking-not-found">All games have started</div>');
                isResultDisplayed = true;
                return;
            }

            betKingData.forEach(function (item, index) {
                leagues.push(item.TournamentName);
                fixtures.push(item.MatchName);
                outcomes.push(item.SelectionName + ' ' + item.SpecialValue);
                odds.push(item.OddValue);
                var date = new Date(item.EventDate);
                dates.push(getDateTimeStrInUTC(date));
            });


            /**
             * This section represent successful retrieval 
             * of booking games.
             */
            var data = {
                leagues: leagues,
                fixtures: fixtures,
                outcomes: outcomes,
                odds: odds,
                totalOdds: totalOdds,
                dates: dates,
                bet_code: code
            };

            $('#booking-code-tab').append(generateTable(data));
            predictionButton.text('Submit Prediction');
            isResultDisplayed = true;
            $('#booking-number').prop('disabled', true);
            $('#betting-type').prop('disabled', true);
            shouldSubmitPrediction = true;

            endStart = getStartEndDateTime(data.dates);

            predictionRes = {
                prediction: JSON.stringify(data),
                start_date_time: endStart.startDateTime,
                end_date_time: endStart.endDateTime,
                type: PLATFORM_BETKING,
            };
        }
    });
}

function fetchSportyBetGames(code) {
    if (isResultDisplayed) {
        var bookingCodetabLastElem = $bookingCodetab.children()[$bookingCodetab.children().length - 1]; // get the last element
        $(bookingCodetabLastElem).remove();
        isResultDisplayed = false;
    };

    if (!$('#booking-number').val()) {
        $bookingCodetab.append('<div class="booking-not-found">Booking code is required, it can not be empty</div>');
        isResultDisplayed = true;
        return;
    }

    predictionButton.text('Loading games...');
    predictionButton.prop('disabled', true);

    $.ajax('/api/web/bet-games', {
        type: 'GET', data: { code: code, type: PLATFORM_SPORTY_BET, token: localStorage.getItem('$$token'), id: $$id },
        success: function (result) {
            var leagues = [];
            var fixtures = [];
            var outcomes = [];
            var odds = [];
            var dates = [];
            predictionButton.text('Submit');
            predictionButton.prop('disabled', false);

            if (result.data.innerMsg != 'success') {
                $bookingCodetab.append('<div class="booking-not-found">Invalid booking code</div>');
                isResultDisplayed = true;
                return;
            }

            var sportyBetData = result.data.data.outcomes;

            sportyBetData.forEach(function (item, index) {
                if (item.matchStatus == 'Not start') {
                    leagues.push(item.sport.category.tournament.name);
                    fixtures.push(item.homeTeamName + ' - ' + item.awayTeamName);
                    outcomes.push(item.markets[0].desc + ' - ' + item.markets[0].outcomes[0].desc);
                    odds.push(item.markets[0].outcomes[0].odds);
                    var date = new Date(item.estimateStartTime);
                    dates.push(getDateTimeStrInUTC(date));
                }
            });

            if (leagues.length == 0) {
                $bookingCodetab.append('<div class="booking-not-found">All the games have started</div>');
                isResultDisplayed = true;
                return;
            }


            /**
             * This section represent successful retrieval 
             * of booking games.
             */
            var data = {
                leagues: leagues,
                fixtures: fixtures,
                outcomes: outcomes,
                odds: odds,
                dates: dates,
                bet_code: code
            };

            $('#booking-code-tab').append(generateTable(data));
            predictionButton.text('Submit Prediction');
            isResultDisplayed = true;
            $('#booking-number').prop('disabled', true);
            $('#betting-type').prop('disabled', true);
            shouldSubmitPrediction = true;

            endStart = getStartEndDateTime(data.dates);
            predictionRes = {
                prediction: JSON.stringify(data),
                start_date_time: endStart.startDateTime,
                end_date_time: endStart.endDateTime,
                type: PLATFORM_SPORTY_BET
            };
        }
    });
}

function getStartEndDateTime(dates) {
    startDateTime = dates[0];
    endDateTime = dates[0];

    dates.forEach(function (item, index) {
        if (dates[index] > endDateTime) {
            endDateTime = dates[index]
        }

        if (dates[index] < startDateTime) {
            startDateTime = dates[index];
        }
    });

    return {
        startDateTime: startDateTime,
        endDateTime: endDateTime
    };
}

function generateTable(data) {
    // bet 1 ZSSLMKR
    // bet 2 
    $table = '<div id="booking-code-table-section"><table style="width:100%; margin-top: 10px;">' +
        '<tr><th>League</th><th>Fixture</th><th>Tip</th><th>Odd</th></tr>';

    data.leagues.forEach(function (item, index) {
        $table += '<tr>' + '<td>' + item + '</td>' + '<td>' + data.fixtures[index] + '</td>' +
            '<td>' + data.outcomes[index] + '</td>' + '<td>' + data.odds[index] + '</td>';
    });

    $table += '<div style="color: unset;" class="booking-not-found">Is this your game? If not <a onclick="removeBookingTable()" style="cursor: pointer;">cancel</a> and try another booking code or prediction method</div>'
    $table += '<span style="font-size: 1.1rem; font-style: italic; margin-top: 2px;"><b>Note: Only games that have not yet started are displayed</b></span></div>';
    return $table;
}

function removeBookingTable() {
    bookingCodetabFisrtElement = $('#booking-code-table-section'); // get the first element
    $(bookingCodetabFisrtElement).remove();
    $('#booking-number').prop('disabled', false);
    $('#betting-type').prop('disabled', false);
    predictionButton.text('Submit');
    isResultDisplayed = false;
    shouldSubmitPrediction = false;
    predictionRes = {};
}

/**
 * =================================================
 * 
 * This section contains all the javascript for cre-
 * ating prediction with fixtures. Users search
 * fixtures by competitions
 * 
 * ==================================================
 */


/**
 * Define global variables for this section
 * like all fixtures, selected fixture etc...
 */
var filteredCompetitions = [];
var filteredFixtures = [];
var filteredOutcomes = [];
var selectedCompetition = {}; // The selected competition
var fixtures = [] // All fixtures gotten;
var currentFixture = {}; // It holds all the fixtures of the selected competition
var selectedMatch = {}; // This hold the selected match by the user AKA a fixture
var selectedOutcome = '';
var isSearchingFixtures = false;


/**
 * Declaration of table data.
 * 
 * Data that will be displayed and also sent 
 * to the server.
 */

var leagues = [];
var selectedFixtures = [];
var outcomes = [];
var teamIds = [];
var dates = [];
var competitionIds = [];



/**
 * Definitions of jquery objects for inputs,
 * dropdowns etc..
 */
$competitionSearch = $('#competition-search'); // competition search input
$competitionDropdown = $('#dropdown-competetion');
$fetchingFixtures = $('#fetching-fixtures'); // div that displays while fetching competition fixtures
$dropdonwFixture = $('#dropdown-fixture');
$searchFixtureInput = $('#fixtures-search'); // fixture search input
$addGame = $('#add-game');
$competitionError = $('#competition-error');
$fixturesError = $('#fixtures-error');
$outcomeError = $('#outcome-error');
$outcomeInput = $('#outcome');
$tableSection = $('#table-section');
$dropdownOutcomes = $('#dropdown-outcomes');


// Show dropdown on focus for competition search input 
$competitionSearch.focus(function () {
    $competitionDropdown.show();
});


// Hide dropdown on focus out for competition search input 
$competitionSearch.focusout(function () {
    // delay by 200 milliseconds so onclick can work
    setTimeout(function () {
        $competitionDropdown.hide();

        // It makes the value of the competition input to the previous
        // value if a user focus out without selecting if user didn't select
        // if a user focus out with selecting it uses the selected competition
        if (Object.keys(currentFixture).length > 0) {
            $competitionSearch.val(getCompetitionName(selectedCompetition));
        }
    }, 200);
});

$competitionSearch.keyup(function () {
    searchCompetition($competitionSearch.val());
    displayDropdownElements();
})

// Show dropdown on focus for fixture search input
$searchFixtureInput.focus(function () {
    if (Object.keys(currentFixture).length > 0) {
        $dropdonwFixture.show();
        searchFixtures($searchFixtureInput.val());
        displayFixtureDropDown();
    }
});

// Hide dropdown on focus out for fixture search input 
$searchFixtureInput.focusout(function () {
    setTimeout(function () {
        $dropdonwFixture.hide();
    }, 200);
});

// search for fixtures by team name
$searchFixtureInput.keyup(function () {
    if (Object.keys(currentFixture).length > 0 && !isSearchingFixtures) {
        $dropdonwFixture.show();
    }
    searchFixtures($searchFixtureInput.val());
    displayFixtureDropDown();
});

$outcomeInput.focus(function () {
    $dropdownOutcomes.show();
    $outcomeError.hide();
    $outcomeInput.removeClass('invalid');
});

$outcomeInput.focusout(function () {
    setTimeout(function () {
        $dropdownOutcomes.hide();
        if (!selectedOutcome) {
            $outcomeInput.val(selectedOutcome);
        }
    }, 200);
});

$outcomeInput.keyup(function () {
    searchOutcomes($outcomeInput.val());
    displayOutcomeDropdown();
});

$addGame.click(function () {
    var isError = false;

    // check if competition has been selected
    if (Object.keys(selectedCompetition).length == 0) {
        $competitionError.show();
        $competitionSearch.addClass('invalid');
        isError = true;
    }

    // Checks if a match has been selected
    if (Object.keys(selectedMatch).length == 0) {
        $fixturesError.show();
        $searchFixtureInput.addClass('invalid');
        isError = true;
    }

    // Checks if a user enters an outcome
    if (!selectedOutcome) {
        $outcomeError.show();
        $outcomeInput.addClass('invalid');
        isError = true;
    }

    /**
     * Check for duplicate predictions
     */
    if (!isError && leagues.length != 0) {
        var lastIndex = leagues.length - 1;
        isSameLeagueWithLastPrediction = leagues[lastIndex] == getCompetitionName(selectedCompetition);
        isSameFixtureWithLastPrediction = selectedFixtures[lastIndex] == selectedMatch.home_name + ' - ' + selectedMatch.away_name;
        isSameOutcomeWithLastPrediction = outcomes[lastIndex] == selectedOutcome;

        if (isSameFixtureWithLastPrediction && isSameLeagueWithLastPrediction && isSameOutcomeWithLastPrediction) {
            isError = true;
            alert('Duplicate prediction');
        }
    }

    if (!isError) {
        leagues.push(getCompetitionName(selectedCompetition));
        selectedFixtures.push(selectedMatch.home_name + ' - ' + selectedMatch.away_name);
        outcomes.push(selectedOutcome);
        teamIds.push(selectedMatch.home_id + '-' + selectedMatch.away_id);
        dates.push(selectedMatch.date + " " + selectedMatch.time);
        competitionIds.push(selectedCompetition.id);
        $tableSection.append(generateTableForFixture());
        $("#table-section").animate({ scrollTop: $("#table-section")[0].scrollHeight }, 1000);

        // Resets state 
        selectedOutcome = '';
        $outcomeInput.val('');
        filteredOutcomes = [];
        searchOutcomes('');
        displayOutcomeDropdown();
    }
});


/**
 * =================================================
 * Start All functions declerations here
 */

/**
 * This filter an assign filters to filteredCompetitions
 * 
 * @param {String} param the parameter we want to search
 */
function searchCompetition(param) {
    filteredCompetitions = [];
    __allCompetitions.forEach(function (competition, index) {
        var countryOrFederationName = competition.countries.length > 0 ? competition.countries[0].name : competition.federations[0].name;
        if (countryOrFederationName.toLowerCase().includes(param.toLowerCase()) || competition.name.toLowerCase().includes(param.toLowerCase())) {
            filteredCompetitions.push(competition);
        }
    });
}


function searchFixtures(param) {
    filteredFixtures = [];
    if (Object.keys(currentFixture).length > 0) {
        currentFixture.data.forEach(function (fixture, index) {
            if (fixture.home_name.toLowerCase().includes(param.toLowerCase()) || fixture.away_name.toLowerCase().includes(param)) {
                filteredFixtures.push(fixture);
            }
        });
    }
}


function searchOutcomes(param) {
    filteredOutcomes = [];
    __outcomes.forEach(function (item, index) {
        if (item.toLowerCase().includes(param.toLowerCase())) {
            filteredOutcomes.push(item);
        }
    });
}

/**
 * It displays competition dropdown element
 */
function displayDropdownElements() {
    $competitionDropdown.empty();
    filteredCompetitions.forEach(function (competition, item) {
        $competitionDropdown.append('<a onclick="selectCompetition(this)" id=' + '"' + competition.id + '"' + '>' + getCompetitionName(competition) + '</a>');
    });
}

function displayFixtureDropDown() {
    $dropdonwFixture.empty();
    filteredFixtures.forEach(function (fixture) {
        var dateStr = fixture.date + ' ' + fixture.time + ' UTC'
        var fixtureDate = new Date(dateStr.replace(/-/g, '/'));

        $html = '<div onclick="selectFixture(this)" style="cursor: pointer;" id=' + '"' + fixture.id + '"' + '>' + '<a>' + fixture.home_name + ' - ' + fixture.away_name + '</a>';
        $html += '<span class="fixture-date">' + formatDateCreated(fixtureDate) + '</span></div>';
        $dropdonwFixture.append($html);
    });
}

function displayOutcomeDropdown() {
    $dropdownOutcomes.empty();
    filteredOutcomes.forEach(function (item, index) {
        $dropdownOutcomes.append('<a onclick="selectOutcome(this)">' + item + '</a>');
    });

}

/**
 * This function is called whenever a user selects
 * a fixture or a match.
 * 
 * @param {*} e the element that was clicked
 */
function selectFixture(e) {
    $fixturesError.hide();
    $searchFixtureInput.removeClass('invalid');
    var matchId = $(e).attr('id');
    for (var i = 0; i < currentFixture.data.length; i++) {
        if (currentFixture.data[i].id == matchId) {
            selectedMatch = currentFixture.data[i];
            $searchFixtureInput.val(selectedMatch.home_name + ' - ' + selectedMatch.away_name);
            return;
        }
    }
}

/**
 * handles onclick for selecting out comes
 * 
 * @param {*} e element selected
 */
function selectOutcome(e) {
    $outcomeError.hide();
    $outcomeInput.removeClass('invalid');
    selectedOutcome = $(e).text();
    $outcomeInput.val(selectedOutcome);
}



/**
 * Given a competition object it returns 
 * the name e.g England Premier league
 */
function getCompetitionName(competition) {
    if (competition.countries.length > 0) {

        return competition.countries[0].fifa_code ? competition.countries[0].fifa_code + ' - ' + competition.name : competition.countries[0].name + ' - ' + competition.name;
    }

    if (competition.federations.length > 0) {
        return competition.federations[0].name + ' - ' + competition.name;
    }

    return competition.name;
}


/**
 * This function handles the action when a user 
 * selects a competition.
 * 
 * What it does is to first
 * go through the list of all the competitions fixtures we 
 * have already if it found which means we have already 
 * loaded it before it assign it and end the function. Else it fetches 
 * or load the data from the server
 * 
 * @param {*} e The clicked element
 */
function selectCompetition(e) {
    $competitionError.hide(); // Hide any error;
    $competitionSearch.removeClass('invalid');

    var competitionId = $(e).attr('id');
    $competitionDropdown.hide();
    var competition = getSelectedCompetition(competitionId);
    selectedCompetition = competition;
    $competitionSearch.val(getCompetitionName(competition));

    // Search for existing fixtures for the selected competition
    for (var index = 0; index < fixtures.length; index++) {
        if (fixtures[index].competitionId == competitionId) {
            currentFixture = fixtures[index];

            // reset selectedMatch because competition has changed
            selectedMatch = {};
            $searchFixtureInput.val('');
            return;
        }
    }
    currentFixture = {};
    getFixtures(competitionId);
}


function getSelectedCompetition(competitionId) {
    for (var index = 0; index < __allCompetitions.length; index++) {
        if (__allCompetitions[index].id == competitionId) {
            return __allCompetitions[index];
        }
    }
}


function getFixtures(competitionId) {
    $fetchingFixtures.show();
    isSearchingFixtures = true;
    $.ajax('/api/web/fixtures', {
        type: 'GET', data: { competition_id: competitionId, token: localStorage.getItem('$$token'), id: $$id },
        success: function (result) {
            isSearchingFixtures = false;
            var tempFixtures = [];
            if (result.success) {
                $fetchingFixtures.hide();
                result.data.forEach(function (item, index) {
                    //tempFixtures.concat(item.fixtures)
                    item.fixtures.forEach(function (item) {
                        tempFixtures.push(item);
                    });
                });
                currentFixture = { competitionId: competitionId, data: tempFixtures }
                fixtures.push(currentFixture);
                // reset selectedMatch because competition has changed
                selectedMatch = {};
                $searchFixtureInput.val('');
            }

        }
    });
}

function generateTableForFixture() {
    $tableSection.empty();
    $table = '<table style="width:100%; margin-top: 10px;">' +
        '<tr><th>Date/Time</th><th>League</th><th>Fixture</th><th>Oucome</th><th>Cancel</th></tr>';

    leagues.forEach(function (item, index) {
        var dateStr = dates[index] + ' UTC';
        var date = new Date(dateStr.replace(/-/g, '/'));
        $table += '<tr>' + '<td>' + formatDateCreated(date) + '</td>' + '<td>' + item + '</td>' + '<td>' + selectedFixtures[index] + '</td>' +
            '<td>' + outcomes[index] + '</td>' + '<td style="text-align:center; color:red;"><i onclick="removePrediction(this)" id="selected-fixture-' + index + '" style="cursor:pointer; font-size:1.9rem;" class="fa fa-times" aria-hidden="true"></i></td>';
    });

    $table += '</table>';
    return $table;
}

function removePrediction(e) {
    var index = $(e).attr('id').split('-')[2];

    leagues.splice(index, 1);
    dates.splice(index, 1);
    selectedFixtures.splice(index, 1);
    outcomes.splice(index, 1);
    competitionIds.splice(index, 1);

    if (leagues.length == 0) {
        $tableSection.empty();
        return;
    }

    $tableSection.append(generateTableForFixture());
    $("#table-section").animate({ scrollTop: $("#table-section")[0].scrollHeight }, 1000);
}




/**
 * End All functions declaration
 * =================================================
 */




 /**
 * =================================================
 * 
 * This section contains all the javascript for 
 * updating prediction with fixtures.
 * 
 * ==================================================
 */

 var updatedScores = [];
 var updatedOutcomeResults = [];

 function getPredictionToEdit(predictionId) {
    __predictionInfo.forEach(function(item, index) {
        if (predictionId == item.prediction_id) {
            return item.prediction;
        }
    });
 }

 function generateTableForUpdate(prediction, predictionId) {
    $tablePredictionUpdateSection.empty();
    $table = '<table style="width:100%; margin-top: 10px;">' +
        '<tr><th>Date/Time</th><th>Fixture</th><th>Scores</th><th>Oucome</th><th>Out.Result</th></tr>';
    
    let scores = prediction.hasOwnProperty('scores') ? prediction.scores : [];
    let score;
    let outcomeResult;
    let outcomeResults = prediction.hasOwnProperty('outcome_results') ? prediction.outcome_results : [];
    prediction.fixtures.forEach(function (item, index) {
        var dateStr = prediction.dates[index] + ' UTC';
        var date = new Date(dateStr.replace(/-/g, '/'));

        score = getScore(item, scores);
        outcomeResult = getOutcomeResult(item, outcomeResults);
        outcomeResult = outcomeResult ? outcomeResult : '<select onchange="selectOutcomeResult(this.value,' + "'" + item + "'" + ',' + predictionId + ')" style="width: 100%; height: 100%;"><option value="-1">Select</option><option value="1">Won</option><option value="0">Lost</option></select>';

        $table += '<tr>' + '<td>' + formatDateCreated(date) + '</td>' + '<td>' + item + '</td>' + '<td>' + (score ? score : '<input onchange="onChangeScoresResult(this.value,' + "'" + item + "'" + ')" type="text" style="width: 100%; height: 100%;">') + '</td>' +
            '<td>' + prediction.outcomes[index] + '</td>' + '<td>' + outcomeResult + '</td>';
    });

    $table += '</table>';
    return $table;
}

function getFixtureIndexForOutcomes(fixture) {
    for (let index = 0; index < updatedOutcomeResults.length; index++) {
        if (updatedOutcomeResults[index].hasOwnProperty(fixture)) {
            return index;
        }
    }

    return -1;
}

function getFixtureIndexForScores(fixture) {
    for (let index = 0; index < updatedScores.length; index++) {
        if (updatedScores[index].hasOwnProperty(fixture)) {
            return index;
        }
    }

    return -1;
}

function selectOutcomeResult(value, fixture, predictionId) {
    if (value != 1 && value != 0) {
        for (let i = 0; i < updatedOutcomeResults.length; i++) {
            if (Object.keys(updatedOutcomeResults[i])[0] == fixture) {
                updatedOutcomeResults.splice(i, 1);
                console.log(updatedOutcomeResults);
                return;
            }
        }
    }

    const index = getFixtureIndexForOutcomes(fixture);
    if (index != -1) {
        updatedOutcomeResults[index][fixture] = value
    } else {
        obj = {};
        obj[fixture] = value;
        updatedOutcomeResults.push(obj);
    }
    console.log(updatedOutcomeResults);
}

function onChangeScoresResult(value, fixture) {
    if (!value.trim()) {
        for (let i = 0; i < updatedScores.length; i++) {
            if (Object.keys(updatedScores[i])[0] == fixture) {
                updatedScores.splice(i, 1);
                console.log(updatedScores);
                return;
            }
        }
    }

    // check if isValid 
    let valueArr = value.split('-');
    console.log(valueArr, '====>>>>');

    if (valueArr.length != 2) {
        return;
    }

    if (!valueArr[0] || !valueArr[1]) {
        return;
    }

    if (isNaN(valueArr[0].trim()) || isNaN(valueArr[1].trim())) {
        return;
    }

    value = valueArr[0] + ' - ' + valueArr[1];

    const index = getFixtureIndexForScores(fixture);

    if (index  != -1) {
        updatedScores[index][fixture] = value
    } else {
        obj = {};
        obj[fixture] = value;
        updatedScores.push(obj);
    }

    console.log(updatedScores);
}

$('#update-prediction').click(function() {
    if (updatedOutcomeResults.length == 0 && updatedScores.length == 0) {
        return;
    }

    var data = {
        prediction_id: predictionIdToUpdate,
        scores: JSON.stringify(updatedScores),
        outcome_results: JSON.stringify(updatedOutcomeResults),
        id: $$id,
        token: token
    };
    // $('#update-prediction').prop('disabled', true);
    $.ajax('/api/web/predictions/update', {
        data: data,
        type: 'POST', 
        success: function (result) {
           // $('#update-prediction').prop('disabled', false);
            console.log(result);
            if (result.success) {
                $('#main-error-update').text('');
                $('#outcome-response-message-update').text(result.message);
                $('#outcome-success-update').show();
                setTimeout(function() {
                    $('outcome-success-update').hide();
                    $('#updatePredictionModal').modal('hide');
                }, 3000);
            } else {
                $('#main-error-update').text(result.messages);
            }
        },
        failure: function() {
            $('#update-prediction').prop('disabled', false);
            console.log('Yes.........');
        }
    });
});


 /**
 * 
 * 
 * This ends the section that contains all the javascript for 
 * updating prediction with fixtures.
 * 
 * ==================================================
 */


