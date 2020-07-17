<?php 
  function isSelf($userId) {
    return isLogin() && $_SESSION['userInfo']['id'] == $userId;
  }

?>

<html class="js sizes customelements history pointerevents postmessage webgl websockets cssanimations csscolumns csscolumns-width csscolumns-span csscolumns-fill csscolumns-gap csscolumns-rule csscolumns-rulecolor csscolumns-rulestyle csscolumns-rulewidth csscolumns-breakbefore csscolumns-breakafter csscolumns-breakinside flexbox picture srcset webworkers" lang="en">
<head>
  <?php include 'Pages/common/Head.php'; ?>
  <link rel="stylesheet" href="/bet_community/Public/css/home.css">
</head>
<body>
<?php include 'Pages/common/Header.php';?>

<div id="page-contents" style="position: relative;">
  <!-- import Modal for reporting -->
  <?php  include 'Pages/modals/ReportModal.php';?>


<?php if (isLogin()): ?>
  <!--Import create prediction Modal start -->
  <?php  include 'Pages/modals/CreatePredictionModal.php';?>

  <!--Import confirm create prediction Modal-->
  <?php  include 'Pages/modals/ConfirmCreatedPredictionModal.php';?>
  
  <!-- Model start of delete confirmation -->
  <?php  include 'Pages/modals/ConfirmPredictionDeleteModal.php';?>

<?php endif; ?>

  <?php if (!isLogin()): ?>
      <!-- Obstruction modal -->
      <?php  include 'Pages/modals/ObstructionModal.php';?>
  <?php endif; ?>
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
                </div>
            		<div  class="col-md-5 col-sm-9">
                    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#createPredictionModal" id="open-prediction-modal">Create Prediction</button>
                </div>
            	</div>
            </div>
            <!-- Post Create Box End-->

          <!-- Post Content
          ================================================= -->
          <?php $prediction = $data['prediction'][0] ?>
          <?php $index = 0; ?>
            <?php $isFollowing = $data['isLogin'] && $controllerObject->isFollowing($prediction['user_id']); ?>
            <div id="<?='prediction-box-' . $prediction['id']?>" class="post-content">
            <div class="dropdown dot-menu">
              <i class="fa fa-ellipsis-h dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
              <div id="menu-action" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <?php if(isLogin() && (int)$_SESSION['userInfo']['role'] > 1): ?>
                  <a id="<?= 'action-menu-' . $prediction['id'] . '-' . (string)$index?>" class="dropdown-item"> <i class="fa fa-user"></i>Prediction  Actions</a>
                  <div class="line-divider"></div>
                <?php endif ?>
                
                <?php if (isSelf($prediction['user_id']) && gmdate("Y-m-d\ H:i:s") < $prediction['start_date']): ?>
                   <a style="color: red;" id="<?= 'dot-menu-delete-' . $prediction['id'] . '-' . (string)$index?>" class="dropdown-item"> <i class="fa fa-trash"></i>
                    Delete Prediction
                  </a>
                  <div class="line-divider"></div>
                <?php endif; ?>

                <?php if (!isSelf($prediction['user_id'])): ?>
                  <a  id="<?= 'dot-menu-' . $prediction['user_id'] . '-' . (string)$index?>" class="dropdown-item"> <i class="<?= $isFollowing ? 'fa fa-user-times' : 'fa fa-user'; ?>"></i>
                    <?= !$isFollowing ? '  Follow     ' . explode(' ', $prediction['name'])[0] : '  Unfollow     ' . explode(' ', $prediction['name'])[0]?>
                  </a>
                  <div class="line-divider"></div>
                <?php endif; ?>

                <a id="<?='report-prediction-' . $prediction['id']?>"  data-toggle="modal" data-target="#reportBugModal" class="dropdown-item"><i class="fa fa-bug"></i> Report Prediction</a>
                <div class="line-divider"></div>
                <a id="<?='copy-prediction-' . $prediction['id']?>" class="dropdown-item"><i class="fa fa-share-alt"></i> Copy Prediction Link</a>
              </div>
            </div>
              <div style="padding-top: 10px;" class="post-container">
              <img style="object-fit: cover;" src="<?=BetCommunity::IMAGES_PATH . $prediction['image_path']?>" alt="user" class="profile-photo-md pull-left">
                <div class="post-detail">
                  <div class="user-info">
                    <h5>
                      <a href="<?='/users/profile?id=' . (string)(BetCommunity::DEFAULT_ADD_PROFILE + $prediction['user_id']) ?>"><?=$prediction['name']?></a>
                      <?php if (!$data['isLogin'] || ($data['isLogin'] && $prediction['user_id'] != $_SESSION['userInfo']['id'])): ?>
                        <a title="<?=$prediction['name']?>" id="<?='follow-' . $prediction['user_id'] . '-' . (string)$index?>" style="<?= $isFollowing ?  'cursor:default;' : 'cursor:pointer;' ?>" class="following"><?=$isFollowing ? 'Following': 'Follow' ?></a>
                      <?php endif ?>
                   </h5>
                    <p id="<?='date-'.$prediction['id']?>" class="text-muted"></p>
                  </div>
                  <div class="reaction">
                    <a id="<?='like-' . $prediction['id'] . '-' . (string)$index?>" class="btn text-green"><i class="icon ion-thumbsup"></i><?=$prediction['total_likes'] > 0 ? $prediction['total_likes'] : '';  ?></a>
                  </div>
                  <div class="line-divider"></div>
                    <div id ="<?='prediction-' . $prediction['id']?>" class="post-text">
                      <div id="<?='prediction-info-' . $prediction['id']?>"  class="bet-info"></div>
                    </div>
                  <div class="line-divider"></div>

                  <div style="margin-bottom: 20px;" class="post-meta">
                      <div class="post-meta-like">
                        <div>
                            <!-- <i class="fa fa-commenting-o ic"><strong><?php /**((int)$prediction['total_comments'] == 0 ? '' : $prediction['total_comments'])*/?></strong></i> -->
                            <!-- <strong>206</strong> -->
                            <span class="status"><b>Status:</b><strong id="<?='outcome-status-' . $prediction['id']?>"><?=$controllerObject->getPredictionStatus($prediction)?></strong></span>
                        </div>
                      </div>
                  </div>
                  <!-- <div class="post-comment">
                    <img src="images/users/user-10.jpg" alt="" class="profile-photo-sm">
                    <p><a href="timeline.html" class="profile-link">Julia </a>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
                  </div>
                  <div class="post-comment">
                    <img src="images/users/user-1.jpg" alt="" class="profile-photo-sm">
                    <input type="text" class="form-control" placeholder="Post a comment">
                  </div> -->
                </div>
              </div>
            </div>
      </div>
          <!-- Newsfeed Common Side Bar Right
          ================================================= -->
          <?php  include 'Pages/common/RightSideBar.php';?>
          
        </div>
        
    	</div>
    </div>

  <?php  include 'Pages/common/Footer.php';?>
  <?php include 'Pages/common/Script.php'?>
  <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script type="text/javascript">var dates=<?=json_encode($data['dates'])?>;</script>
    <script type="text/javascript">var __allCompetitions=<?=json_encode($data['competitions'])?>;</script>
    <script type="text/javascript">var __predictionInfo=<?=json_encode($data['predictionInfo'])?>;</script>
    <script type="text/javascript">var __outcomes=<?=json_encode($data['outcomes'])?>;</script>
    <script src="/bet_community/Public/js/prediction.js"></script>
    <script src="/bet_community/Public/js/common-sidebar.js"></script>
    <script>
      setTimeout(() => {
        $($('#f-users-wrapper').children()[1]).css('pointer-events', 'none');
      }, 500);
    </script>
    <script src="/bet_community/Public/js/script.js"></script>
  </body>
</html>
