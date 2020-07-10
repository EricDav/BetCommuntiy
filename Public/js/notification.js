
$(document).ready(function() {
    const DEFAULT_ADD_PROFILE = '/bet_community/Public/images/users/';
    var readTexcolor = 'rgb(124, 126, 130)';
    var notifications = [];
    var show = false;
    var isCleared = false;
    var unseenNotification = 0;

    $notificationWrapper = $('#mySidenav');
    function goToNotification() {
        alert('Here');
        console.log($(this).attr('data'));
        window.location.href = window.location.origin + $(this).attr('data');
    }

    $('#notification-bell').click(function() {
        clearSeen();
        if (show) {
            $notificationWrapper.hide();
        } else {
            $notificationWrapper.show();
        }

        show = !show;
    });

    $('.closebtn').click(function() {
        $notificationWrapper.hide();
        show = false;
    });

    function generateNotificationHtml(notificationObj) {
        var $html = '<div data=' + '"' + notificationObj.link + '"' + ' id="notification" class="feed-item">';
        $html+= '<img src=' + '"' + DEFAULT_ADD_PROFILE +  notificationObj.image_path + '"' + ' alt="user" class="img-responsive profile-photo-sm"></img>';
        $html+= '<div class="live-activity">';
        $html+= '<p class="notification-text"' + (notificationObj.is_read ? 'style="color: #777; font-weight: unset;"' : '') + '><a href="#" class="profile-link">' + notificationObj.name.split(' ')[0] + '</a>' + '<a href="' + notificationObj.link + '" style="color: unset;">' + notificationObj.notification + '</a>' + '</p>';
        $html+= '<p class="text-muted">' + formatDateForPrediction(new Date(notificationObj.created_at.replace(/-/g, '/'))) + '</p>';
        $html+= '</div>';
        $html+= '</div>';

        return $html;
    }

    function getNotifications() {
        $.ajax('/api/web/notifications?id=' + $$id + '&token=' + token, {
            type: 'GET',  success: function(result) {
                console.log(result);
                if (result.success) {
                    notifications = result.data;
                    var $notificationWrapper = $('#mySidenav');
                    result.data.forEach(function(item) {
                        if (!item.is_seen) {
                            unseenNotification +=1;
                        }
                        $notificationWrapper.append(generateNotificationHtml(item));
                    });
                    if (unseenNotification > 0) {
                        $('#unseen-notification').text(unseenNotification.toString());
                        $('#unseen-notification').css('background-color', 'red');
                    }
                }
       }});
    }

    function clearSeen() {
        if (unseenNotification > 0) {
            $.ajax('/api/web/notifications/clear-seen', { data: {id: $$id, token: token},
                type: 'POST',  success: function(result) {
                   if (result.success) {
                       isCleared = true;
                       $('#unseen-notification').text('');
                       $('#unseen-notification').css('background-color', 'unset');
                   }
           }});
        }
    }

    getNotifications();
});
