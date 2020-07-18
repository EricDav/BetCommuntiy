const OUTCOME_WON_STATUS_ICON = '<i style="color: green; font-size: 20px;" class="fa fa-check"></i>';
const OUTCOME_LOST_STATUS_ICON = '<i style="color: red; font-size: 20px;" class="fa fa-check"></i>';
const OUTCOME_PREFIX_TEXT = 'outcome-status-';

$(document).ready(function(){
    function determinePredictionOutcome(data, status) {
        $.ajax('/api/web/predictions/determine-outcome', {
            data: data,
            type: 'POST', success: function (result) {
                $('#outcome-response-message').text(result.message);
                if (result.success) {
                    $('#outcome-success').show();
                    var $statusJqueryObj = $('#' + OUTCOME_PREFIX_TEXT + data.prediction_id);
                    var text = data.status == 0 ? OUTCOME_LOST_STATUS_ICON : OUTCOME_WON_STATUS_ICON;

                    $statusJqueryObj.html('');
                    $statusJqueryObj.html(text);
                    setTimeout(function() {
                        $('#outcome-success').hide();
                        $('#predictionOutcomeModal').modal('hide');
                    }, 3000);
                    selectedOutcomePrediction.won = data.status;
                }
            }
        });
    }

    $('#prediction-won').click(function(){
        determinePredictionOutcome({id: $$id, token: token, prediction_id: selectedOutcomePrediction.prediction_id, status: 1});
    });

    $('#prediction-lost').click(function(){
        determinePredictionOutcome({id: $$id, token: token, prediction_id: selectedOutcomePrediction.prediction_id, status: 0});
    });
});
