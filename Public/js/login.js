
var message = '';
var loginEmail = $('#my-email');
var loginPassword = $('#my-password');

var signupEmail = $('#email');
var signupPassword = $('#password');
var firstName = $('#firstname');
var lastName = $('#lastname');
var city = $('#city');
var sexMale = $('#sex-male');
const FOLLOW_ELEMENT_ID_PREFIX = 'follow-';
const DOT_MENU_ELEMENT_ID_PREFIX = 'dot-menu-'


$("#login_button").click(function(){

    if (!loginEmail.val().trim() && !loginPassword.val().trim()) {
        $('#my-email').addClass('invalid');
        $('#my-password').addClass('invalid');
        message = 'Email and password are required';
    } else if (!loginEmail.val().trim()) {
        $('#my-email').addClass('invalid');
        message = 'Email is required';
    } else if (!loginPassword.val().trim()) {
        $('#my-password').addClass('invalid');
        message = 'Password is required';
    } else {
        $.ajax('/login', { data: {type: 'login', 'email': loginEmail.val(), 'password' :loginPassword.val()},
         type: 'POST',  success: function(result) {
            console.log(result);
            // response = JSON.parse(result)
           if (!result.success) {
                $('#error-message').text(result.messages);
           } else {
               window.location = '/';
           }
        }});
    }

    if (message != '') {
        $('#error-message').text(message);
    }
});

/**
 * handles sign up button click
 */
$('#signup-button').click(function() {
    var empty = [];

    /**
     * validates if inputs are not empty
     */
    if (!firstName.val().trim()) empty.push('First Name');
    if (!lastName.val().trim()) empty.push('Last Name');
    if (!city.val().trim()) empty.push('City');
    if (!signupEmail.val().trim()) empty.push('Email');
    if (!signupPassword.val().trim()) empty.push('Password');


    if (empty.length == 1) {
        message = empty[0] + ' is required';
        $('#signup-error-message').text(message)
    }

    if (empty.length > 1) { 
        var message = '';
        empty.forEach(function(item, index) {
            message+=item;
            if (index == empty.length - 2) 
                message+=' and ';
            
            if (index < empty.length - 2)
                message+=', ';
        });
        message +=' are required';
        $('#signup-error-message').text(message);
    }

    if (empty.length == 0) {
        console.log(sexMale.attr('checked'));
        var sex = sexMale.is(':checked') ? 'Male' : 'Female';
        var postData = {
            type: 'signup', 
            email: signupEmail.val(), 
            password :signupPassword.val(),
            city: city.val(),
            firstName: firstName.val(),
            lastName: lastName.val(),
            country: $('#country').val(),
            sex: sex
        };

        $.ajax('/login', { data: postData,
            type: 'POST',  success: function(result) {
             console.log(result);
           // response = JSON.parse(result)
            if (!result.success) {
                message = '';
                mArr = []
                for (const property in result.messages) {
                    mArr.push(result.messages[property]);
                }
                if (mArr.length == 1) {
                    message = mArr[0];
                } else {
                    mArr.forEach(function(item, index) {
                        message+=item;
                        if (index == mArr.length - 2) {
                            message+=' and ';
                         }
            
                        if (index < mArr.length - 2) {
                            message+=', ';
                        }
                    });   
                }
                $('#signup-error-message').text(message);
            } else {
                window.location = '/';
            }
       }});
    } 

})

function removeErrorBorder() {
    if (message != '') {
        $(this).removeClass('invalid');
    }
}

// login email
$('#my-email').focus(removeErrorBorder);
$('#my-password').focus(removeErrorBorder);


