<html>
    <head>
        <title>Contact | Bet Community</title>
        <?php
            /**
             * include head styles
             */
            include 'Pages/common/Head.php';
        ?>
        <link rel="stylesheet" href="/bet_community/Public/css/contact.css">
    </head>
    <body>
        <?php
            include 'Pages/common/Header.php';
        ?>
        <div class="google-maps">
            <div id="map" class="map contact-map" style="position: relative; overflow: hidden;"><div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);"><div class="gm-err-container"><div class="gm-err-content"><div class="gm-err-icon"><img src="https://maps.gstatic.com/mapfiles/api-3/images/icon_error.png" draggable="false" style="user-select: none;"></div><div class="gm-err-title">Sorry! Something went wrong.</div><div class="gm-err-message">This page didn't load Google Maps correctly. See the JavaScript console for technical details.</div></div></div></div></div>
        </div> 
        <div id="page-contents">
    	    <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="contact-us">
                            <div class="row">
                                <div class="col-md-8 col-sm-7">
                                    <div>
                                        <h4 class="grey">Leave a Message</h4>
                                    </div>
                                    <div style = 'width: 100%; position: relative; text-align: right'>
                                        <i><h6 style = 'font-size: times-new-roman' class="text-danger contact_error_user_log"></h6></i>
                                    </div>
                                    <form class="contact-form" _lpchecked="1">
                                        <?php
                                            $full_name  = "";
                                            $email      = "";
                                            $specialId  = "";
                                            $disabled = "";
                                            
                                            if(contactController::isSessionSet()){
                                                $full_name = $_SESSION['userInfo']['name'];
                                                $email = $_SESSION['userInfo']['email'];
                                                $specialId = $_SESSION['userInfo']['specialId'];
                                                $disabled = 'disabled';
                                            }
                                            
                                        ?>
                                        <div class="form-group">
                                            <i class="icon ion-person"></i>
                                            <input value = "<?php echo $full_name?>" <?php echo $disabled;?> id="contact-name" type="text" name="name" class="contact_name_field form-control" placeholder="Full Name *" required="required" data-error="Name is required." style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAfBJREFUWAntVk1OwkAUZkoDKza4Utm61iP0AqyIDXahN2BjwiHYGU+gizap4QDuegWN7lyCbMSlCQjU7yO0TOlAi6GwgJc0fT/fzPfmzet0crmD7HsFBAvQbrcrw+Gw5fu+AfOYvgylJ4TwCoVCs1ardYTruqfj8fgV5OUMSVVT93VdP9dAzpVvm5wJHZFbg2LQ2pEYOlZ/oiDvwNcsFoseY4PBwMCrhaeCJyKWZU37KOJcYdi27QdhcuuBIb073BvTNL8ln4NeeR6NRi/wxZKQcGurQs5oNhqLshzVTMBewW/LMU3TTNlO0ieTiStjYhUIyi6DAp0xbEdgTt+LE0aCKQw24U4llsCs4ZRJrYopB6RwqnpA1YQ5NGFZ1YQ41Z5S8IQQdP5laEBRJcD4Vj5DEsW2gE6s6g3d/YP/g+BDnT7GNi2qCjTwGd6riBzHaaCEd3Js01vwCPIbmWBRx1nwAN/1ov+/drgFWIlfKpVukyYihtgkXNp4mABK+1GtVr+SBhJDbBIubVw+Cd/TDgKO2DPiN3YUo6y/nDCNEIsqTKH1en2tcwA9FKEItyDi3aIh8Gl1sRrVnSDzNFDJT1bAy5xpOYGn5fP5JuL95ZjMIn1ya7j5dPGfv0A5eAnpZUY3n5jXcoec5J67D9q+VuAPM47D3XaSeL4AAAAASUVORK5CYII=&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                                        </div>
                                        <div class="form-group">
                                            <i class="icon ion-email"></i>
                                            <input value ="<?= $email?>"  <?php echo $disabled;?> id="contact-email" type="text" name="email" class="contact_email_field form-control" placeholder="Email *" required="required" data-error="Email is required.">
                                        </div>
                                        
                                        <div class="form-group">
                                            <textarea id="form-message" name="message" class="contact_message_field form-control" placeholder="Leave a message for us *" rows="4" required="required" data-error="Please,leave us a message."></textarea>
                                        </div>
                                    </form>
                                    <button class="contact_send_button btn-primary">Send a Message</button>
                                </div>
            		            <div class="col-md-4 col-sm-5">
                                    <h4 class="grey">Reach Us</h4>
                                    <div class="reach"><span class="phone-icon"><i class="icon ion-android-call"></i></span><p>+1 (234) 222 0754</p></div>
                                    <div class="reach"><span class="phone-icon"><i class="icon ion-email"></i></span><p>info@thunder-team.com</p></div>
                                    <div class="reach"><span class="phone-icon"><i class="icon ion-ios-location"></i></span><p>228 Park Ave S NY, USA</p></div>
                                    <ul class="list-inline social-icons">
                                        <li><a href="#"><i class="icon ion-social-facebook"></i></a></li>
                                        <li><a href="#"><i class="icon ion-social-twitter"></i></a></li>
                                        <li><a href="#"><i class="icon ion-social-googleplus"></i></a></li>
                                        <li><a href="#"><i class="icon ion-social-pinterest"></i></a></li>
                                        <li><a href="#"><i class="icon ion-social-linkedin"></i></a></li>
                                    </ul>
                                </div>
            	            </div>
                        </div>
                    </div>
    		    </div>
    	    </div>
        </div>
        <?php include 'Pages/common/Footer.php';?>
        <?php include 'Pages/common/Script.php'?>
        <script src = '/bet_community/Public/js/contact.js'></script>
        <script src="/bet_community/Public/js/script.js"></script>
    </body>
</html>
