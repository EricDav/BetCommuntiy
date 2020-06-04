
var userId= 1;
$('#LearnMore').on('click', function(){
    $.ajax('/notification', 
    {   
        /**
         * data for getting notification
         */
        // data: 
        // {
        //     'type' : 'getNotification',
        //     'userId' : userId
        // },

        /**
         * data for registering own notification
         */
        data:
        {
            'type' : 'registerUsersNotification',
            'userId' : userId,
            'message' : 'Shola sent you notification',
            'recipient' : 'self' || 'followers'
        },

        type: 'POST',

        success: function(result){
            console.log(result);
        }
    })
})
