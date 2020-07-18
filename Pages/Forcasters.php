<html class="js sizes customelements history pointerevents postmessage webgl websockets cssanimations csscolumns csscolumns-width csscolumns-span csscolumns-fill csscolumns-gap csscolumns-rule csscolumns-rulecolor csscolumns-rulestyle csscolumns-rulewidth csscolumns-breakbefore csscolumns-breakafter csscolumns-breakinside flexbox picture srcset webworkers" lang="en">
<head>
  <?php include 'Pages/common/Head.php'; ?>
  <link rel="stylesheet" href="<?='/bet_community/Public/css/forcasters.css?v=' . BetCommunity::CSS['forcasters.css']?>">
</head>
<body>
<?php include 'Pages/common/Header.php';?>

<div id="page-contents" style="position: relative;">
    <div class="container">
    	<div class="row">

          <!-- Newsfeed Common Side Bar Left
          ================================================= -->
    			<div class="col-md-3 static">
            <?php include 'Pages/common/LeftSideBar.php';?>
          </div>
    			<div class="col-md-7">

            <!-- Post Create Box
            ================================================= -->
            <div class="create-post">
            	<div class="row">
            		<div class="col-md-7 col-sm-7">
                    <?=$data['paginationHtml']?>
                </div>
            	</div>
            </div>
            <!-- Post Create Box End-->

          <!-- Friend List =================================================  Start-->
            <?php include 'Pages/common/Forecaster.php'; ?>
          <!-- Friend List =================================================  End-->

        </div>
          <!-- Newsfeed Common Side Bar Right
          ================================================= -->
          <?php  include 'Pages/common/RightSideBar.php';?>
    		</div>
    	</div>
    </div>
    <?php  include 'Pages/common/Footer.php';?>
    <?php include 'Pages/common/Script.php'?>
    <script src="<?='/bet_community/Public/js/common-sidebar.js?v=' . BetCommunity::JS['common-sidebar.js']?>"></script>
    <script src="<?='/bet_community/Public/js/follow.js?v=' .  BetCommunity::JS['follow.js']?>"></script>
    <script>
      setTimeout(() => {
        $($('#f-users-wrapper').children()[1]).css('pointer-events', 'none');
      }, 500);
    </script>
    <script src="/bet_community/Public/js/script.js"></script>
  </body>
</html>
