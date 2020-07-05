$(document).ready(function(){





    /**
     * variables
     */
    emailField = $('#myEmail');
    info = $('#info_container');//variables





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
    $.fn.ajaxSendResetRequest = function(email, package){
        $.ajax({
            type:'POST',
            url:'/forgot-password',
            data: package, 
            success: function(result){
                console.log(result);
                
                if(result.trim() !== ''){
                    result = JSON.parse(result);
                    if(result['success'] === true){
                        message = result['messages'];
                        status = 'success';
                        $.fn.setReturnInformation(message, status);
                        if('url' in result){
                            window.location.replace('/login')
                        }
                    }else{
                        message = result['messages'];
                        status = 'failure';
                        $.fn.setReturnInformation(message, status);
                    }   
                }
            }
        })
    }





    /**
     * bind click event to the send button
     * 
     */
    $('#send_reset_link_button').unbind().click(function(){
        $.fn.clearReturnInformation();
        sendButton = $(this);
        sendButton.prop('disabled', true);



        if($.fn.isEmailEmpty(emailField.val())){
            message = " Email field cannot be left empty"
            status = 'warning'
            $.fn.setReturnInformation(message, status)
            sendButton.prop('disabled', false);
        }else{
            package = {
                'request':'send_reset_link',
                'email': emailField.val()
            };
            $.fn.ajaxSendResetRequest(emailField.val(), package);
            sendButton.prop('disabled', false);
        }


    })





    /**
     * Bind click event to  reset button
     */
    $('#reset_button').click(function(){
        $.fn.clearReturnInformation();
        var resetButton = $(this);
        resetButton.prop('disabled', true);
        $('#newPassword').prop('disabled', true);
        $('#newPasswordDuplicate').prop('disabled', true);
        var newPassword = $('#newPassword').val();
        var newPasswordDuplicate = $('#newPasswordDuplicate').val();
        
        if($.fn.isEmailEmpty(newPassword)){
            message = " Password Required"
            status = 'warning'
            $.fn.setReturnInformation(message, status)
            resetButton.prop('disabled', false);
            $('#newPassword').prop('disabled', false);
            $('#newPasswordDuplicate').prop('disabled', false);
        }else if($.fn.isEmailEmpty(newPasswordDuplicate)){
            message = " Retype Password"
            status = 'warning'
            $.fn.setReturnInformation(message, status)
            resetButton.prop('disabled', false);
            $('#newPassword').prop('disabled', false);
            $('#newPasswordDuplicate').prop('disabled', false);
        }else{
            package = {
                'request':'reset_password',
                'password': newPassword,
                'passwordDuplicate': newPasswordDuplicate
            };
            $.fn.ajaxSendResetRequest(emailField.val(), package);
            resetButton.prop('disabled', false);
            $('#newPassword').prop('disabled', false);
            $('#newPasswordDuplicate').prop('disabled', false);

        }
    })
});