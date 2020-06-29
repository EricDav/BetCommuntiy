$('#today-prediction').click(function() {
    let now = new Date();

    var month = now.getMonth() + 1;
    var minutes = now.getMinutes();
    if (minutes.length == 1) {
        minutes = '0' + minutes;
    }

    date = now.getFullYear() + '-' + month.toString() + '-' + now.getDate() +
        ' ' + now.getHours() + ':' + minutes;

    window.location.href = window.location.origin + '/?filter_day=today&current_date=' + date;
});

$('#yesterday-prediction').click(function() {
    let now = new Date();

    var month = now.getMonth() + 1;
    var minutes = now.getMinutes();

    date = now.getFullYear() + '-' + month.toString() + '-' + now.getDate() +
        ' ' + now.getHours() + ':' + minutes;

    window.location.href = window.location.origin + '/?filter_day=yesterday&current_date=' + date;
});

$('#correct-prediction').click(function() {
    window.location.href = window.location.origin + '/?filter_status=won';
});
