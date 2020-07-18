// $(document).ready(function() {
    const CALLING_FROM_PROFILE_PAGE = 1;
    const CALLING_FROM_FORECASTER_PAGE = 2;

    var numFollowers = Number.parseInt($('#num-followers').attr('data'));
    $('#profile-follow').click(function() {
        followUserWithoutUpdate(CALLING_FROM_PROFILE_PAGE);
    });

    $('#profile-follow-mobile').click(function() {
        followUserWithoutUpdate(CALLING_FROM_PROFILE_PAGE);
    });

    /**
     * It calls the AJAX request that follows a user and also unfollow in some cases
     * 
     * @param {*} callingElem 
     * @param {*} callingFrom Which page we calling this function as it is a shared function
     * @param {*} userId The userid 
     */
    function followUserWithoutUpdate(callingFrom, userId=null) {
        // Checks if user is logged in;
        if ($$id == -1) {
            $('#login-modal-txt').html('You need to <a href="/login" style="cursor: pointer;">login or signup</a> to follow ' + __name);
            $('#follow-note').text('Note: following a user means you get notified when they drop predictions');
            $('#createPredictionModal').modal('show');
            return;
        }

        var userId = userId ? userId : __userId; // if I am calling this function from forecaster page user id will be passed
        var isFollowingId;
        if (callingFrom == CALLING_FROM_PROFILE_PAGE) {
            isFollowingId = isFollowing ? 1 : 0;
        }

        if (callingFrom == CALLING_FROM_FORECASTER_PAGE)
            isFollowingId = 0;
        
        data = {
            token: localStorage.getItem('$$token'),
            id: $$id,
            action_type: 'follow', 
            user_id: userId, 
            is_following: isFollowingId
        };
    
        $.ajax('/api/web/users-action', { data: data, type: 'POST',  success: function(result) {
               if (result.success) {
                    if (callingFrom == CALLING_FROM_PROFILE_PAGE) {
                        numFollowers = isFollowing ? numFollowers - 1 : numFollowers + 1;

                        isFollowing = !isFollowing;
                        var text = isFollowing ? 'Unfollow' : 'Follow';
                        $('#profile-follow').text(text);
                        $('#num-followers').text('(' + numFollowers + ')');
                        $('#profile-follow-mobile').text(text);
                        $('#num-followers-mobile').text('(' + numFollowers + ')');
                    } else if (callingFrom == CALLING_FROM_FORECASTER_PAGE) {
                        var jqueryObj = $('#forecaster-follow-' + userId.toString());
                        jqueryObj.prop('onclick', null);
                        jqueryObj.text('Following');
                        // jqueryObj.css('cursor', )
                    }
               }
                return false;
            }
        })
    }
// })

