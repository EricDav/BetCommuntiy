<html class="js sizes customelements history pointerevents postmessage webgl websockets cssanimations csscolumns csscolumns-width csscolumns-span csscolumns-fill csscolumns-gap csscolumns-rule csscolumns-rulecolor csscolumns-rulestyle csscolumns-rulewidth csscolumns-breakbefore csscolumns-breakafter csscolumns-breakinside flexbox picture srcset webworkers" lang="en">
<head>
  <?php include 'Pages/common/Head.php'?>
  <link rel="stylesheet" href="<?='/bet_community/Public/css/forgotPassword.css?v=' . BetCommunity::CSS['forgotPassword.css']?>">
</head>
<body>
    <?php include 'Pages/common/Header.php'?>
    <div id="lp-register">
    <div class="container wrapper">
        <div class="row">
            <div class="col-sm-5">
            <div class="intro-texts">
                <h1 class="text-white">Connect With Bet Lovers !!!</h1>
                <p>Bet Community is a social network platform that can be used to connect people that have passion in bettings. 
            This platform help people to be able to share there betting tips.<br>
                <br>What are you waiting for? Join now.</p>
            <button class="btn btn-primary">Learn More</button>
            </div>
        </div>
        <div class="col-sm-6 col-sm-offset-1" style="margin-top: -40px;">
          <div class="reg-form-container"> 
                   
            <!--Forgot password-->
            <?php
              /**
               * page content
               */
              $alert = "";
              if($request->route == '/forgot-password'){
                /**
                 * Display error message
                 */
                
                if($data['message'] != "" && $message !== true){
                  $alert = "<div style = 'padding: 5px !important' class = 'alert alert-danger'>
                              <span class = 'fa fa-exclamation-circle'></span>
                              <span>".$message."</span>
                            </div>";
                }


                echo "<div class=\"tab-pane\" id=\"login\">
                        <h3>Forgot password</h3>
                        <p class=\"text-muted\">Enter the email address associated with your account. We will send you 
                        a code that you will use to reset your password</p>
                        <div id = 'info_container' class = 'alert'>
                        ".$alert."
                      </div>
                        <!--forgot password form-->
                        <form name=\"Login_form\" id=\"Login_form\">
                            <div class=\"row\">
                              <div class=\"form-group col-xs-12\">
                                <label for=\"myEmail\" class=\"sr-only\">Email</label>
                                <input id=\"myEmail\" class=\"form-control input-group-lg\" type=\"email\" name=\"email\" title=\"Enter Email\" placeholder=\"Your Email\" style=\"background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAkCAYAAADo6zjiAAAAAXNSR0IArs4c6QAAAbNJREFUWAntV8FqwkAQnaymUkpChB7tKSfxWCie/Yb+gbdeCqGf0YsQ+hU95QNyDoWCF/HkqdeiIaEUqyZ1ArvodrOHxanQOiCzO28y781skKwFW3scPV1/febP69XqarNeNTB2KGs07U3Ttt/Ozp3bh/u7V7muheQf6ftLUWyYDB5yz1ijuPAub2QRDDunJsdGkAO55KYYjl0OUu1VXOzQZ64Tr+IiPXedGI79bQHdbheCIAD0dUY6gV6vB67rAvo6IxVgWVbFy71KBKkAFaEc2xPQarXA931ot9tyHphiPwpJgSbfe54Hw+EQHMfZ/msVEEURjMfjCjbFeG2dFxPo9/sVOSYzxmAwGIjnTDFRQLMQAjQ5pJAQkCQJ5HlekeERxHEsiE0xUUCzEO9AmqYQhiF0Oh2Yz+ewWCzEY6aYKKBZCAGYs1wuYTabKdNNMWWxnaA4gp3Yry5JBZRlWTXDvaozUgGTyQSyLAP0dbb3DtQlmcan0yngT2ekE9ARc+z4AvC7nauh9iouhpcGamJeX8XF8MaClwaeROWRA7nk+tUnyzGvZrKg0/40gdME/t8EvgG0/NOS6v9NHQAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;\" autocomplete=\"off\" required>
                              </div>
                              <div style=\"display: none;\" id=\"token-wrapper\" class=\"form-group col-xs-12\">
                                <label for=\"myCode\" class=\"sr-only\">Email</label>
                                <input id=\"myCode\" class=\"form-control input-group-lg\" type=\"email\" name=\"code\" title=\"code\" placeholder=\"Enter 6 digit code sent\" style=\"background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAkCAYAAADo6zjiAAAAAXNSR0IArs4c6QAAAbNJREFUWAntV8FqwkAQnaymUkpChB7tKSfxWCie/Yb+gbdeCqGf0YsQ+hU95QNyDoWCF/HkqdeiIaEUqyZ1ArvodrOHxanQOiCzO28y781skKwFW3scPV1/febP69XqarNeNTB2KGs07U3Ttt/Ozp3bh/u7V7muheQf6ftLUWyYDB5yz1ijuPAub2QRDDunJsdGkAO55KYYjl0OUu1VXOzQZ64Tr+IiPXedGI79bQHdbheCIAD0dUY6gV6vB67rAvo6IxVgWVbFy71KBKkAFaEc2xPQarXA931ot9tyHphiPwpJgSbfe54Hw+EQHMfZ/msVEEURjMfjCjbFeG2dFxPo9/sVOSYzxmAwGIjnTDFRQLMQAjQ5pJAQkCQJ5HlekeERxHEsiE0xUUCzEO9AmqYQhiF0Oh2Yz+ewWCzEY6aYKKBZCAGYs1wuYTabKdNNMWWxnaA4gp3Yry5JBZRlWTXDvaozUgGTyQSyLAP0dbb3DtQlmcan0yngT2ekE9ARc+z4AvC7nauh9iouhpcGamJeX8XF8MaClwaeROWRA7nk+tUnyzGvZrKg0/40gdME/t8EvgG0/NOS6v9NHQAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;\" autocomplete=\"off\" required>
                            </div>
                            </div>
                        </form><!--forgot password form ends--> 
                        <button id=\"send_reset_link_button\" class=\"btn btn-primary\">Send</button>
                        <br>
                        <br>
                      </div>";
              }else{
                $name = $_SESSION['temp_user']['name'];
                echo "<div class=\"tab-pane\" id=\"login\">
                        <h3>Reset Password</h3>
                        
                        <p class=\"text-muted\">
                          <span>Set new password </span>
                          <span style = 'font-size: 12px' class=\"text-primary\">(".$name.")</span>
                        </p>                        
                        <div id = 'info_container' class = 'alert'>
                        </div>
                        <!--forgot password form-->
                        <form name=\"Login_form\" id=\"Login_form\">
                            <div class=\"row\">
                              <div class=\"form-group col-xs-12\">
                                <label for=\"myPassword\" class=\"sr-only\">New Password</label>
                                <input id=\"newPassword\" class=\"form-control input-group-lg\" type=\"password\" name=\"password\" title=\"New Password\" placeholder=\"New Password\" style=\"background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAkCAYAAADo6zjiAAAAAXNSR0IArs4c6QAAAbNJREFUWAntV8FqwkAQnaymUkpChB7tKSfxWCie/Yb+gbdeCqGf0YsQ+hU95QNyDoWCF/HkqdeiIaEUqyZ1ArvodrOHxanQOiCzO28y781skKwFW3scPV1/febP69XqarNeNTB2KGs07U3Ttt/Ozp3bh/u7V7muheQf6ftLUWyYDB5yz1ijuPAub2QRDDunJsdGkAO55KYYjl0OUu1VXOzQZ64Tr+IiPXedGI79bQHdbheCIAD0dUY6gV6vB67rAvo6IxVgWVbFy71KBKkAFaEc2xPQarXA931ot9tyHphiPwpJgSbfe54Hw+EQHMfZ/msVEEURjMfjCjbFeG2dFxPo9/sVOSYzxmAwGIjnTDFRQLMQAjQ5pJAQkCQJ5HlekeERxHEsiE0xUUCzEO9AmqYQhiF0Oh2Yz+ewWCzEY6aYKKBZCAGYs1wuYTabKdNNMWWxnaA4gp3Yry5JBZRlWTXDvaozUgGTyQSyLAP0dbb3DtQlmcan0yngT2ekE9ARc+z4AvC7nauh9iouhpcGamJeX8XF8MaClwaeROWRA7nk+tUnyzGvZrKg0/40gdME/t8EvgG0/NOS6v9NHQAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;\" autocomplete=\"off\" required>
                              </div>
                              <div class=\"form-group col-xs-12\">
                                <label for=\"myPasswordDublicate\" class=\"sr-only\">Re-Enter Password</label>
                                <input id=\"newPasswordDuplicate\" class=\"form-control input-group-lg\" type=\"password\" name=\"re-password\" title=\"Re-Enter Password\" placeholder=\"Re-Enter Password\" style=\"background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAkCAYAAADo6zjiAAAAAXNSR0IArs4c6QAAAbNJREFUWAntV8FqwkAQnaymUkpChB7tKSfxWCie/Yb+gbdeCqGf0YsQ+hU95QNyDoWCF/HkqdeiIaEUqyZ1ArvodrOHxanQOiCzO28y781skKwFW3scPV1/febP69XqarNeNTB2KGs07U3Ttt/Ozp3bh/u7V7muheQf6ftLUWyYDB5yz1ijuPAub2QRDDunJsdGkAO55KYYjl0OUu1VXOzQZ64Tr+IiPXedGI79bQHdbheCIAD0dUY6gV6vB67rAvo6IxVgWVbFy71KBKkAFaEc2xPQarXA931ot9tyHphiPwpJgSbfe54Hw+EQHMfZ/msVEEURjMfjCjbFeG2dFxPo9/sVOSYzxmAwGIjnTDFRQLMQAjQ5pJAQkCQJ5HlekeERxHEsiE0xUUCzEO9AmqYQhiF0Oh2Yz+ewWCzEY6aYKKBZCAGYs1wuYTabKdNNMWWxnaA4gp3Yry5JBZRlWTXDvaozUgGTyQSyLAP0dbb3DtQlmcan0yngT2ekE9ARc+z4AvC7nauh9iouhpcGamJeX8XF8MaClwaeROWRA7nk+tUnyzGvZrKg0/40gdME/t8EvgG0/NOS6v9NHQAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;\" autocomplete=\"off\" required>
                              </div>
                            </div>
                        </form><!--forgot password form ends--> 
                        <button id=\"reset_button\" class=\"btn btn-primary\">Reset</button>
                        <br>
                        <br>
                    </div>";
              }
            ?>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-sm-offset-6">
          
            <!--Social Icons-->
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
    <div id="spinner-wrapper" style="display: none;">
      <div class="spinner"></div>
    </div>
    <?php include 'Pages/common/Script.php'?>
    <?php if (isset($_SESSION['temp_user'])): ?>
      <script type="text/javascript">var __code=<?=json_encode($data['code'])?>;</script>
    <?php endif ?>
    <script src="<?='/bet_community/Public/js/forgotPassword.js?v=' . BetCommunity::JS['forgotPassword.js']?>"></script>
    <script src="/bet_community/Public/js/script.js"></script>
</body>
</html>