/**
 * It displays the date when each prediction
 * is made. 
 */
dates.forEach(function(item, index) {
    date = new Date(item.date_created + ' UTC');
    $('#date-' + item.id.toString()).text(formatDateCreated(date));
});

function calculateOdds(odds) {
    resultOdds = 1;

    odds.forEach(function(odd) {
        resultOdds*=Number.parseFloat(odd);
    });

    return resultOdds.toFixed(2);
}

function generatePredictionInfoHtml(prediction, predictionType) {
    $html = '<div>No.Selection:<span><b>' + prediction.leagues.length.toString() + '</b></span></div>';
    if (prediction.bet_code) {
        $html +='<div>Selection Type:<span><b>' + predictionType + '</b></span></div>';
        $html +='<div>Booking Code:<span><b>' + prediction.bet_code + '</b></span></div>';
        $html +='<div>Total Odds:<span><b>' + calculateOdds(prediction.odds) + '</b></span></div>';
    }
    return $html;
}

function generatePredictionTable(data) {
    $table = '<table style="width:100%; margin-top: 10px; border:unset;">' +
                 '<tr style="border:unset;"><th style="border:unset;">Date/Time</th><th style="border:unset;">League</th><th style="border:unset;">Fixture</th><th style="border:unset;">Oucome</th></tr>';
    
    data.leagues.forEach(function(item, index) {
        if (data.dates) {
            var dateStr = data.dates[index] + ' UTC';
            var date = new Date(dateStr.replace(/-/g, '/'));
        }

        $table += '<tr style="border:unset;">' + '<td style="border:unset;">' + (data.dates ? formatDateCreated(date) : 'NS') + '</td>' + '<td style="border:unset;">' + item + '</td>' + '<td style="border:unset;">' + data.fixtures[index] + '</td>' + 
            '<td style="border:unset;">' + data.outcomes[index] + '</td>';
    });

    $table += '</table>';
    return $table;
}

/**
 * It displays prediction data
 */
__predictionInfo.forEach(function (item, index) {
    var prediction = JSON.parse(item.prediction);
    item.prediction = prediction;
    $('#prediction-info-' + item.prediction_id).append(generatePredictionInfoHtml(prediction, item.prediction_type));
    $('#prediction-' + item.prediction_id).append(generatePredictionTable(prediction));
});


