$(document).ready(function() {
    /**
 * Declare jquary object for Profile page tabs
 */
var $profileAboutTab = $('#profile-about');
var $profileFollowersTab = $('#profile-followers');
var $profileFollowersTabMobile = $('#profile-followers-mobile');
var $profilePredictionTab = $('#profile-predictions');
var $profileAboutTabMobile = $('#profile-about-mobile');
var $profilePredictionTabMobile = $('#profile-predictions-mobile');
var $profileAboutWrapper = $('#profile-about-wrapper');
var $profilePredictionWrapper = $('#profile-prediction-wrapper');
var $editProfileSideBar = $('#edit-profile-side-bar');
var $profileFollowersWrapper = $('#followers-wrapper');

/**
 * Declare jquery object for edit profile side
 * bar tabs
 */
 var $editProfileBasic = $('#edit-profile-basic-side');
 var $accountSettingsSide = $('#account-settings-side');
 var $changePasswordSide = $('#change-password-side');
 var $editProfileBasicWrapper = $('#edit-profile');
 var $accountSettingsSideWrapper = $('#account-settings');
 var $changePasswordWrapper = $('#change-password');

 /**
  * Declare jquery object for edit profile inputs
  */
 var $firstNameInput = $('#firstname');
 var $lastNameInput = $('#lastname');
 var $emailInput = $('#email');
 var $phoneNumberInput = $('#phonenumber');
 var $cityInput = $('#city');
 var $submitButton = $('#submit-update-btn');
 var $errorMessage = $('#edit-profile-error-message');
 var $sexMale = $('#sex-male');

 /**
  * Declare jquery object for change password inputs
  */
  var $oldPasswordInput = $('#my-password');
  var $newPasswordInput = $('#new-password');
  var $confirmPasswordInput = $('#confirm-password');
  var $updatePasswordButton = $('#update-password');
  var $changePassworErrorMessage = $('#change-password-error-message');


var lastActive = $profilePredictionTab;
var lastWrapper = $profilePredictionWrapper;

$profileAboutTab.click(function() {
    // Do nothing if the last tab is the same as the current 
    // This means the user clicks the same tab more than once
    if ($profileAboutTab == lastActive || $profileAboutTabMobile == lastActive) {
        return;
    }
    $profileAboutWrapper.show();
    $editProfileSideBar.show();
    lastActive.removeClass('active');

    $profileAboutTab.addClass('active');
    lastWrapper.hide();
    lastActive = $profileAboutTab;
    lastWrapper = $profileAboutWrapper;
});

$profileFollowersTab.click(function() {
    // Do nothing if the last tab is the same as the current 
    // This means the user clicks the same tab more than once
    if ($profileFollowersTab == lastActive || $profileFollowersTabMobile == lastActive) {
        return;
    }
    $profileFollowersWrapper.show()
    lastActive.removeClass('active');

    $profileFollowersTab.addClass('active');
    lastWrapper.hide();
    $editProfileSideBar.hide();
    lastActive = $profileFollowersTab;
    lastWrapper = $profileFollowersWrapper;
});

$profilePredictionTabMobile.click(function() {
    // Do nothing if the last tab is the same as the current 
    // This means the user clicks the same tab more than once
    if ($profilePredictionTabMobile == lastActive || $profilePredictionTab == lastActive) {
        return;
    }
    $profilePredictionWrapper.show();
    $profilePredictionTabMobile.addClass('active');

    lastActive.removeClass('active');
    lastWrapper.hide();
    // $profileAboutWrapper.hide();
    $editProfileSideBar.hide();
    lastActive = $profilePredictionTabMobile;
    lastWrapper = $profilePredictionWrapper;
});

$profileFollowersTabMobile.click(function() {
    // Do nothing if the last tab is the same as the current 
    // This means the user clicks the same tab more than once
    if ($profileFollowersTabMobile == lastActive || $profileFollowersTab == lastActive) {
        return;
    }

    $profileFollowersWrapper.show()
    lastActive.removeClass('active');

    $profileFollowersTab.addClass('active');
    lastWrapper.hide();
    $editProfileSideBar.hide();
    lastActive = $profileFollowersTabMobile;
    lastWrapper = $profileFollowersWrapper;
});

$profileAboutTabMobile.click(function() {
    // Do nothing if the last tab is the same as the current 
    // This means the user clicks the same tab more than once
    if ($profileAboutTabMobile == lastActive || $profileAboutTab == lastActive) {
        return;
    }
    $profileAboutWrapper.show();
    $editProfileSideBar.show();
    lastActive.removeClass('active');

    $profileAboutTabMobile.addClass('active');
    lastWrapper.hide();
    lastActive = $profileAboutTabMobile;
    lastWrapper = $profileAboutWrapper;
});

$profilePredictionTab.click(function() {
    // Do nothing if the last tab is the same as the current 
    // This means the user clicks the same tab more than once
    if ($profilePredictionTab == lastActive) {
        return;
    }

    $profilePredictionWrapper.show();
    $profilePredictionTab.addClass('active')
    lastActive.removeClass('active');

    lastWrapper.hide();
    $editProfileSideBar.hide();
    lastActive = $profilePredictionTab;
    lastWrapper = $profilePredictionWrapper;
});

var lastActiveForEditProfileTab = $editProfileBasic;
var lastWrapperVisible = $editProfileBasicWrapper;

$editProfileBasic.click(function() {
    lastWrapperVisible.hide();
    lastActiveForEditProfileTab.removeClass('active');

    $editProfileBasicWrapper.show();
    $editProfileBasic.addClass('active');

    lastWrapperVisible = $editProfileBasicWrapper;
    lastActiveForEditProfileTab = $editProfileBasic;

});

$accountSettingsSide.click(function() {
    lastWrapperVisible.hide();
    lastActiveForEditProfileTab.removeClass('active');

    $accountSettingsSide.addClass('active');
    $accountSettingsSideWrapper.show();

    lastWrapperVisible = $accountSettingsSideWrapper;
    lastActiveForEditProfileTab = $accountSettingsSide;

});

$changePasswordSide.click(function() {
    lastWrapperVisible.hide();
    lastActiveForEditProfileTab.removeClass('active');

    $changePasswordSide.addClass('active');
    $changePasswordWrapper.show();

    lastWrapperVisible = $changePasswordWrapper;
    lastActiveForEditProfileTab = $changePasswordSide;
});

var message = '';
var sex = $sexMale.is(':checked') ? 'Male' : 'Female';
var initialData = {
    firstName: $firstNameInput.val(),
    lastName: $lastNameInput.val(),
    city: $cityInput.val(),
    email: $emailInput.val(),
    phoneNumber: $phoneNumberInput.val(),
    sex: sex,
    country: $('#country').val()
}

/** 
 * Handle onclick for submitting the update
 * user profile
 */
$submitButton.click(function() {
    var empty = [];

    /**
     * validates if inputs are not empty
     */
    if (!$firstNameInput.val().trim()) {
        $firstNameInput.addClass('invalid')
        empty.push('First Name');
    } 
    if (!$lastNameInput.val().trim()) { 
        $lastNameInput.addClass('invalid');
        empty.push('Last Name');
    }

    if (!$cityInput.val().trim()) {
        $cityInput.addClass('invalid')
        empty.push('City');
    }
    if (!$emailInput.val().trim()) {
        $emailInput.addClass('invalid')
        empty.push('Email');
    }

    if (empty.length == 1) {
        message = empty[0] + ' is required';
        $errorMessage.text(message);
    }

    if (empty.length > 1) {
        message = '';
        empty.forEach(function(item, index) {
            message+=item;
            if (index == empty.length - 2) 
                message+=' and ';
            
            if (index < empty.length - 2)
                message+=', ';
        });
        message +=' are required';
        $errorMessage.text(message);
        return;
    }

    var newSex = $sexMale.is(':checked') ? 'Male' : 'Female';
    var firstName = $firstNameInput.val();
    var lastName = $lastNameInput.val();
    var city = $cityInput.val();
    var email = $emailInput.val();
    var phoneNumber =$phoneNumberInput.val();
    var country = $('#country').val();

    var isProfileUpdated = isUpdated(firstName, lastName, city, email, newSex, phoneNumber, country);
    if (isProfileUpdated) {
        var data = {
            action: 'update_profile',
            firstName: firstName,
            lastName: lastName,
            city: city,
            email: email,
            phoneNumber: phoneNumber,
            sex: newSex,
            country: country,
            token: localStorage.getItem('$$token'),
            id: $$id,
        }

        updateProfile(data);
    }
});

$updatePasswordButton.click(function() {
    var empty = [];

    if (!$oldPasswordInput.val()) {
        $oldPasswordInput.addClass('invalid')
        empty.push('Old password');
    }

    if (!$newPasswordInput.val()) {
        $newPasswordInput.addClass('invalid')
        empty.push('New password');
    }

    if (!$confirmPasswordInput.val()) {
        $confirmPasswordInput.addClass('invalid')
        empty.push('Confirm password');
    }

    if (empty.length > 1) {
        message = '';
        empty.forEach(function(item, index) {
            message+=item;
            if (index == empty.length - 2) 
                message+=' and ';
            
            if (index < empty.length - 2)
                message+=', ';
        });
        message +=' are required';
        $changePassworErrorMessage.text(message);
        return;
    }

    if ($confirmPasswordInput.val() != $newPasswordInput.val()) {
        $changePassworErrorMessage.text('Confirm password and new password does not match');
        return;
    }

    data = {
        action: 'update_password',
        oldPassword: $oldPasswordInput.val(),
        password: $newPasswordInput.val(),
        token: localStorage.getItem('$$token'),
        id: $$id,
    }

    updateProfile(data);
});

function updateProfile(data) {
    if (data.action == 'update_password') {
        $updatePasswordButton.attr('disabled', true);
        $updatePasswordButton.text('Saving...');
    }

    if (data.action == 'update_profile') {
        $submitButton.attr('disabled', true);
        $submitButton.text('Saving...');
    }

    $.ajax('/api/web/update-profile', { data: data,
        type: 'POST',  success: function(result) {
        if (data.action == 'update_password') {
            $updatePasswordButton.attr('disabled', false);
            $updatePasswordButton.text('Save Changes');
        }

        if (data.action == 'update_profile') {
            $submitButton.attr('disabled', false);
            $submitButton.text('Save Changes'); 
        }

         if (result.success) {
            if (data.type == 'update_profile') {
                initialData = {
                    firstName: data.firstName,
                    lastName: data.lastName,
                    city: data.city,
                    email: data.email,
                    phoneNumber: data.phoneNumber,
                    sex: data.sex,
                    country: data.country
                };
            }

            $changePassworErrorMessage.text('');
            $oldPasswordInput.val('');
            $newPasswordInput.val('');
            $confirmPasswordInput.val('');
            $('.alert-success').show();

            setTimeout(function() {
                $('.alert-success').hide();
            }, 2000)

         } else {
            setErrorMessage(result.messages, true);
         }
   }});
}

function setErrorMessage(message, isPassword=false) {
    if (typeof message == 'string') {
        if (isPassword) {
            $changePassworErrorMessage.text(message)
        } else {
            $errorMessage.text(message);
        }
        return;
    } 

    var msg = '';
    var count = 0;
    length = Object.keys(message);
    for (key in message) {
        if (count < length - 1) {
            msg+=message[key] + ', ';
        } else {
            msg+=message[key];
        }
    }

    if (isPassword) {
        $changePassworErrorMessage.text(msg);
    } else {
        $errorMessage.text(msg);
    }
}

function isUpdated(firstName, lastName, city, email, sex, phoneNumber, country ) {
    return !(initialData.firstName == firstName && initialData.lastName == lastName && initialData.city == city &&
        initialData.email == email && initialData.sex == sex && initialData.phoneNumber == phoneNumber && 
        initialData.country == country);
}

function removeErrorBorder() {
    if (message != '') {
        $(this).removeClass('invalid');
    }
}

 $('._b3').click(function() {
    $('#my-file').click();
 });

 $("#my-file").change(function(e){
    readURL(this, 'image-preview');
});

$('#upload-photo').click(function() {
    var fd = new FormData();
    var files = $('#my-file')[0].files[0];
    fd.append('file',files);

    document.cookie = "id=" + $$id + "; " + " path=/";
    document.cookie = "token=" + localStorage.getItem('$$token') + "; " + " path=/";

    $('#upload-photo').attr('disabled', true);
    $('#upload-photo').text('Uploading...');
    $.ajax({
        url: '/api/web/update-profile',
        type: 'post',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
            // Deletes the cookies by setting the expiring date to the past
            document.cookie = "id=" + $$id + "; expires=Thu, 01 Jan 1970 00:00:00 UTC; " + " path=/";
            document.cookie = "token=" + localStorage.getItem('$$token') + "; expires=Thu, 01 Jan 1970 00:00:00 UTC;" + " path=/";
            if (response.success) {
                $('#upload-photo').attr('disabled', false);
                $('#upload-photo').text('Upload');

                $('#profile-picture').attr('src', response.url);
                $('#profile-picture-mobile').attr('src', response.url);
                $('#header-image').attr('src', response.url);

                $('.alert-danger').hide();
                $('.alert-success').show();
                $('.alert-success').text(response.message)
                setTimeout(function() {
                    $('.alert-danger').hide();
                    $('.alert-success').hide();
                    $('#myModal').modal('hide');
                }, 3000);

            } else {
                $('#alert-success').hide();
                $('#alert-danger').show();
                $('#alert-danger').text(response.message)
            }
        },
    });
});

function readURL(input, id, showModal=true) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#' + id).attr('src', e.target.result);
            if (showModal) 
                $('#myModal').modal('show')
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        console.log('I got here baby girl!!!');
    }
}

$firstNameInput.focus(removeErrorBorder);
$lastNameInput.focus(removeErrorBorder);
$emailInput.focus(removeErrorBorder);
$cityInput.focus(removeErrorBorder);

$oldPasswordInput.focus(removeErrorBorder);
$newPasswordInput.focus(removeErrorBorder);
$confirmPasswordInput.focus(removeErrorBorder);

});