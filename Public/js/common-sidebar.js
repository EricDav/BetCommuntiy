$('#today-prediction').click(function() {
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
    // console.log(date); return;

    window.location.href = window.location.origin + '/?filter_day=today&current_date=' + date;
});

$('#yesterday-prediction').click(function() {
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

    window.location.href = window.location.origin + '/?filter_day=yesterday&current_date=' + date;
});

$('#correct-prediction').click(function() {
    window.location.href = window.location.origin + '/?filter_status=won';
});
