var startDate = $('#start-date');
var startTime = $('#start-time');
var endDate = $('#end-date');
var endTime = $('#end-time');
var prediction = $('#prediction');
var totalOdds = $('#total-odds');
var monthsArr = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

dates.forEach(function(item, index) {
    date = new Date(item.date_created + ' UTC');
    $('#date-' + item.id.toString()).text(formatDateCreated(date));
});

function formatDateCreated(date) {
    var now = new Date();

    var  nowYear = now.getFullYear();
    var nowMonth = now.getDate();
    var nowDay = now.getDay();

    var  year = date.getFullYear();
    var month = date.getDate();
    var day = date.getDay();
    var hours = date.getHours();
    var minutes = date.getMinutes();

    if (nowYear == year && nowMonth == month && nowDay  == day) {
        return 'Today at ' + format(date.getHours()) + ':' + format(date.getMinutes());
    }

    if (nowYear == year && nowMonth == month && nowDay - 1 == day) {
        return 'Yesterday at ' + format(date.getHours()) + ':' + format(date.getMinutes());
    }

   if (year == nowYear) {
       return day.toString() + ' ' + monthsArr[month] + ' at '  + format(hours) + ':' + format(minutes);
   }
    
    return day.toString() + ' ' + monthsArr[month] + ' ' + year.toString() + ' at '  + format(hours) + ':' + format(minutes);
}

var inputsArr = [startDate, startTime, endDate, endTime, prediction, totalOdds];

$('#prediction-submit').click(function() {
    clearError(); // Clear error messages 

    error = {}; // saves id of the p tag to display error messages
    errorArr = []; // saves id of input element so as to display error red border to show required elements

    /**
     * Checks for empty input elements and saves the 
     * element in the errorArr if empty.
     */
    inputsArr.forEach(function(item, index) {
        if (!item.val()) {
            errorArr.push(item.attr('id'));
        }
    });

    /**
     * This checks if error exist in the start date
     * and saves error messages in the @eerror object if they exist
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
            console.log('Inside here =>>>>');
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
     * This block of code display tthe red border for empty input
     */
    errorArr.forEach(function(item, index){
        $('#' + item).addClass('invalid');
    });

    if (errorArr.length == 0 && Object.keys(error).length == 0) {
        var lines = prediction.val().split('\n');
        var predictionStr = '';
        for(var i = 0;i < lines.length;i++){
            if (lines[i].trim())
                predictionStr+= lines[i] + '<br>';
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
        
        $.ajax('/api/web/create-prediction', { data: data,
            type: 'POST',  success: function(result) {
            console.log(result);
            if (!result.success) {
                message = '';
                count = Object.keys(result.messages) - 1;
                counter = 0;
                for(property in result.messages) {
                    if (counter != count) {
                        messages+=result.messages[property] + ', ';
                    } else {
                        message+=result.messages[property];
                    }
                }
                $('#main-error').text(message);
            }
       }});
    }
});

function format(d) {
    d = d.toString();
    return d.length == 2 ? d : '0' + d;
}

function clearError() {
    inputsArr.forEach(function(item, index) {
        item.removeClass('invalid');
    });
    errorMessagesIds = ['#start', '#end', '#predict', '#odds'];

    errorMessagesIds.forEach(function(item, index) {
        $(item).text('');
    });
    $('#main-error').text('');
}

