<html class="js sizes customelements history pointerevents postmessage webgl websockets cssanimations csscolumns csscolumns-width csscolumns-span csscolumns-fill csscolumns-gap csscolumns-rule csscolumns-rulecolor csscolumns-rulestyle csscolumns-rulewidth csscolumns-breakbefore csscolumns-breakafter csscolumns-breakinside flexbox picture srcset webworkers" lang="en">
<head>
  <?php include 'Pages/common/Head.php'; ?>
  <link rel="stylesheet" href="/bet_community/Public/css/forcasters.css">
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
            		<div  class="col-md-5 col-sm-9">
                    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#exampleModal" id="open-prediction-modal">Create Prediction</button>
                </div>
            	</div>
            </div>
            <!-- Post Create Box End-->

          <!-- Friend List =================================================  Start-->
          <div class="friend-list">
            <div class="row">
              <?php foreach($data['forcasters'] as $forcaster): ?>
                <div class="col-md-6 col-sm-6">
                  <div class="friend-card">
                  	<img src="bet_community/Public/images/covers/champions.jpg" alt="profile-cover" class="img-responsive cover">
                  	<div class="card-info">
                      <img style="object-fit: cover;" src="<?=BetCommunity::IMAGES_PATH . $forcaster['image_path']?>" alt="user" class="profile-photo-lg">
                      <div class="friend-info">
                        <a class="pull-right text-green">Follow</a>
                      	<h5><a href="<?='/users/profile?id=' . (string)(BetCommunity::DEFAULT_ADD_PROFILE + $forcaster['id']) ?>" class="profile-link"><?=$forcaster['name']?></a></h5>
                        <p>Total Predictions: <b><?=$forcaster['total_predictions']?></b></p>
                        <span>Correct Predictions: <b><?=$forcaster['total_predictions_won']?> </b><i style="color: green;" class="fa fa-check"></i></span>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach ?>
            </div>
          </div>
          <!-- Friend List =================================================  End-->

        </div>
          <!-- Newsfeed Common Side Bar Right
          ================================================= -->
    	<div class="col-md-2 static">
            <div class="suggestions is_stuck" id="sticky-sidebar" style="position: fixed; top: -3px; width: 155px;">
              <h4 class="grey"><b>Featured Users</b></h4>
              <?php foreach($data['featuredUsers'] as $featuredUser): ?>
                <div class="follow-user">
                  <img src="images/users/user-15.jpg" alt="" class="profile-photo-sm pull-left">
                  <div>
                    <h5><a href="timeline.html"><?=$featuredUser['name']?></a></h5>
                    <a id="<?=$featuredUser['id']?>" class="text-green"><?=$controllerObject->isFollowing($prediction['user_id']) ? 'Following': 'Follow' ?></a>
                  </div>
                </div>
              <?php endforeach ?>
            </div><div style="position: static; width: 155px; height: 374px; display: block; vertical-align: baseline; float: none;"></div>
          </div>


    		</div>
    	</div>
    </div>
    <?php  include 'Pages/common/Footer.php';?>
    <?php include 'Pages/common/Script.php'?>
    <script src="/bet_community/Public/js/common-sidebar.js"></script>
  </body>
</html>
