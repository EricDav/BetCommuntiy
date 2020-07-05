$(document).ready(function(){
    $('.contact_send_button').on('click', function(){
        $('.contact_error_user_log').text("");
        var button = $(this);
        // button.prop('disabled', true);
        var fullName = $(".contact_name_field").val().trim();
        var email = $(".contact_email_field").val().trim();
        var message = $(".contact_message_field").val().trim();
        var fields = {};
        var emptyFields = new Array();
        var error = "";
        var fieldsArray = [
            $('.contact_name_field'),
            $('.contact_email_field'),
            $('.contact_message_field')
        ];

        /**
         * Pass each field name and value into an object
         * loop to collect empty fields
         * foreach empty field design an error message and remove the error
         */
        var fields = {
            "Full Name" : fullName,
            "Email": email,
            "Message": message
        };

        for(item in fields){
            if(fields[item] ==""){
                emptyFields.push(item);
            }  
        }
     
        var length = emptyFields.length
        var x = 0;
        if(length !== 0){
            
            emptyFields.forEach(function(item, index){
                if(length == 1){
                    error = item;
                }else{
               
                    if(x == (length-2)){
                        error += item +' ';
                    }else if(x == (length-1)){
                        error += 'and ' + item;
                    }else{
                        error += item + ', ';
                    }
                }
                x++;
            });
            


           error = error + ' cannot be left empty';
           $('.contact_error_user_log').text(error);
           


            /**
             * Create red border and remove it
             */
           fieldsArray.forEach(function(item, index){
                if(item.val().trim() == ""){
                    item.css({
                        'border':'1px solid red'
                    })
                    item.on('focus', function(){
                        $(this).css({
                            'border':'1px solid #27aae1'
                        })
                    })
                    item.on('blur', function(){
                        $(this).css({
                            'border':'1px solid #f1f2f2'
                        })
                    })
                }
           })

           /**
            * undisable button
            */
           button.prop('disabled', false);




        }else{



            /**
             * Send an ajax request to validate and store contact data
             */

            $.ajax({
                url: "/contact",
                type: "POST",
                data: {
                    'data' : fields
                },
                success: function(result){
                    // console.log(result);
                    button.prop('disabled', false);
                    if(result !== ""){
                        if(result['success'] === false){
                            $('.contact_error_user_log').text(result['message']);
                            if('field_index' in result){
                                fieldsArray[result['field_index']].css({
                                    'border':'1px solid red'
                                })
                                fieldsArray[result['field_index']].on('focus', function(){
                                    $(this).css({
                                        'border':'1px solid #27aae1'
                                    })
                                })
                                fieldsArray[result['field_index']].on('blur', function(){
                                    $(this).css({
                                        'border':'1px solid #f1f2f2'
                                    })
                                })
                            }
                        }else if(result['success'] === true){
                            $('.contact_error_user_log').html(
                                "<p class = 'text-success'>"+
                                    result['message']+
                                "</p>"
                            );

                            fieldsArray.forEach(function(item, index){
                                if(item.prop("disabled") === false){
                                    item.val("");
                                }
                            })
                        }
                    }else{
                        return false;
                    }
                }
            })
        }
    })
})