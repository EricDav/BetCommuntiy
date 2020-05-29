
$("#login_button").click(function(){

    var message = '';
    var email = $('#email').val();
    var password = $('#password').val()

    if (!email.trim() && !password.trim()) {
        $('#email').addClass('invalid');
        $('#password').addClass('invalid');
        message = 'Email and password are required';
    } else if (!email.trim()) {
        $('#email').addClass('invalid');
    } else if (!password.trim()) {
        $('#password').addClass('invalid');
    } 
    $.ajax('/login', 
    { type: 'POST',  success: function(result){
       console.log(result);
    }});
});
