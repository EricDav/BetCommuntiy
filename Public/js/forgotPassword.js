$(document).ready(function(){





    /**
     * variables
     */
    var emailField = $('#myEmail');
    var codeField = $('#myCode');
    var info = $('#info_container');//variables
    var isCodeSent = false;





    /**
     * Display status message 
     */
    $.fn.setReturnInformation = function(message, status){
        if(status == 'warning'){
            info.addClass('alert-warning');
            info.append(
                "<span class = 'fa fa-exclamation-circle'></span>"+
                "<span>"+
                    " "+message+
                "</span>"
            );
        }  
        if(status == 'failure'){
            info.addClass('alert-danger');
            info.append(
                "<span class = 'fa fa-exclamation-circle'></span>"+
                "<span>"+
                    " "+message+
                "</span>"
            );
        }
        if(status == 'success'){
            info.addClass('alert-success');
            info.append(
                "<span class = 'fa fa-check-circle'></span>"+
                "<span>"+
                    " "+message+
                "</span>"
            );
        }
    }//end of display status message




    /**
     * Clear return information
     */
    $.fn.clearReturnInformation = function(){
        info.html("");
        info.prop("class", "");
        info.addClass('alert');
    }//end of clear return information





    /**
     * Validate empty email field
     */
    $.fn.isEmailEmpty = function(email){
        if(email == ""){
            return true;
        }else{
            return false;
        }
    }




    /**
     * Handle ajax request
     */
    $.fn.ajaxSendResetRequest = function(email, package, url, text, button){
        button.prop('disabled', true);
        button.text(text + 'ing...');

        $.ajax({
            type:'POST',
            url:url,
            data: package, 
            success: function(result){
               button.prop('disabled', false);
               button.text(text);

                if(result.success){
                    if (url == '/forgot-password/reset') {
                        status = 'success';
                        $.fn.setReturnInformation(result.message, status);
                        isCodeSent = true;
                        setTimeout(function() {
                            window.location.href = '/login';
                        }, 3000);
                    } else {
                        if (!isCodeSent) {
                            $('#token-wrapper').show();
                            $('#myEmail').attr('disabled', true);
                            message = result['message'];
                            status = 'success';
                            $.fn.setReturnInformation(message, status);
                            isCodeSent = true;
                        } else {
                            window.location.href = '/forgot-password/reset';
                        }
                    }
                } else{
                    message = result['message'];
                    status = 'failure';
                    $.fn.setReturnInformation(message, status);
                }   
            }
        });
    }





    /**
     * bind click event to the send button
     * 
     */
    $('#send_reset_link_button').unbind().click(function(){
        $.fn.clearReturnInformation();
        sendButton = $(this);

        if($.fn.isEmailEmpty(emailField.val())){
            message = " Email field cannot be left empty"
            status = 'warning'
        } else {
            if (isCodeSent) {
                if (!codeField.val()) {
                    message = " Code field cannot be left empty";
                    status = 'warning';
                    $.fn.setReturnInformation(message, status);
                    return;
                }

                package = {
                    'request':'validate_code',
                    'email': emailField.val(),
                    'code': codeField.val()
                };

            } else {
                package = {
                    'request':'send_reset_link',
                    'email': emailField.val()
                };
            }
            $.fn.ajaxSendResetRequest(emailField.val(), package, '/forgot-password', 'Send', sendButton);
        }
    });

    /**
     * Bind click event to  reset button
     */
    $('#reset_button').click(function(){
        $.fn.clearReturnInformation();
        var resetButton = $(this);
        var newPassword = $('#newPassword').val();
        var newPasswordDuplicate = $('#newPasswordDuplicate').val();
        
        if($.fn.isEmailEmpty(newPassword)){
            message = " Password Required"
            status = 'warning'
            $.fn.setReturnInformation(message, status)
        }else if($.fn.isEmailEmpty(newPasswordDuplicate)){
            message = " Retype Password"
            status = 'warning'
            $.fn.setReturnInformation(message, status)
        }else if (newPassword != newPasswordDuplicate) {
            message = " Password doesn't match"
            status = 'warning'
            $.fn.setReturnInformation(message, status)
        } else{
            package = {
                'request':'reset_password',
                'password': newPassword,
                'passwordDuplicate': newPasswordDuplicate,
                'code': __code
            };

            $.fn.ajaxSendResetRequest(emailField.val(), package, '/forgot-password/reset', 'Reset', resetButton);
        }
    })
});
