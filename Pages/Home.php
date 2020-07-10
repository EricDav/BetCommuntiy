<?php 
  /**
   * Given a competition object it returns 
   * the name e.g England Premier league
   */
  function getCompetitionName($competition) {
    if (sizeof($competition->countries) > 0) {
      return $competition->countries[0]->fifa_code ? $competition->countries[0]->fifa_code . ' - ' . $competition->name :  $competition->countries[0]->name . ' - ' . $competition->name;
    }

    if (sizeof($competition->federations) > 0) {
      return $competition->federations[0]->name . ' - ' . $competition->name;
    }

    return $competition->name;
  }

  function isSelf($userId) {
    return isLogin() && $_SESSION['userInfo']['id'] == $userId;
  }

?>

<html class="js sizes customelements history pointerevents postmessage webgl websockets cssanimations csscolumns csscolumns-width csscolumns-span csscolumns-fill csscolumns-gap csscolumns-rule csscolumns-rulecolor csscolumns-rulestyle csscolumns-rulewidth csscolumns-breakbefore csscolumns-breakafter csscolumns-breakinside flexbox picture srcset webworkers" lang="en">
<head>
  <?php include 'Pages/common/Head.php'; ?>
  <link rel="stylesheet" href="/bet_community/Public/css/home.css">
  <link rel="stylesheet" href="/bet_community/Public/css/notification.css">
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
        <?php if (isLogin()): ?>
          <!-- Obstruction modal -->
          <?php  include 'Pages/common/Notifications.php';?>
        <?php endif; ?>
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
            		<div class="col-md-7">
                    <?=$data['paginationHtml']?>
                </div>
            		<div id="create-but-wrapper"  class="col-md-5">
                    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#createPredictionModal" id="open-prediction-modal">Create Prediction</button>
                </div>
            	</div>
            </div>
            <!-- Post Create Box End-->

          <!-- Post Content
          ================================================= -->
          <?php if(sizeof($data['predictions']) == 0): ?>
            <div class="no-prediction"> No Predictions Found </div>
          <?php endif ?>

          <?php $index = 0; ?>
          <?php foreach($data['predictions'] as $prediction): ?>
            <?php $isFollowing = $data['isLogin'] && $controllerObject->isFollowing($prediction['user_id']); ?>
            <div id="<?='prediction-box-' . $prediction['id']?>" class="post-content">
            <div class="dropdown dot-menu">
              <i class="fa fa-ellipsis-h dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
              <div id="menu-action" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <?php if($data['isLogin'] && (int)$_SESSION['userInfo']['role'] > 1): ?>
                  <a class="dropdown-item"> <i class="fa fa-user"></i>  Action</a>
                <?php endif ?>
                
                <?php if (isSelf($prediction['user_id'])): ?>
                   <a style="color: red;" id="<?= 'dot-menu-delete-' . $prediction['id'] . '-' . (string)$index?>" class="dropdown-item"> <i class="fa fa-trash"></i>
                    Delete Prediction
                  </a>
                <?php endif; ?>

                <?php if (!isSelf($prediction['user_id'])): ?>
                  <a  id="<?= 'dot-menu-' . $prediction['user_id'] . '-' . (string)$index?>" class="dropdown-item"> <i class="<?= $isFollowing ? 'fa fa-user-times' : 'fa fa-user'; ?>"></i>
                    <?= !$isFollowing ? '  Follow     ' . explode(' ', $prediction['name'])[0] : '  Unfollow     ' . explode(' ', $prediction['name'])[0]?>
                  </a>
                <?php endif; ?>

                <div class="line-divider"></div>
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
                    <a class="btn text-green"><i class="icon ion-thumbsup"></i> 2</a>
                  </div>
                  <div class="line-divider"></div>
                    <div id ="<?='prediction-' . $prediction['id']?>" class="post-text">
                      <div id="<?='prediction-info-' . $prediction['id']?>"  class="bet-info"></div>
                    </div>
                  <div class="line-divider"></div>

                  <div style="margin-bottom: 20px;" class="post-meta">
                      <div class="post-meta-like">
                        <div>
                            <i class="fa fa-commenting-o ic"><strong><?=((int)$prediction['total_comments'] == 0 ? '' : $prediction['total_comments'])?></strong></i>
                            <!-- <strong>206</strong> -->
                            <span class="status"><b>Status:</b><strong><i><?=$controllerObject->getPredictionStatus($prediction)?></i></strong></span>
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
            <?php $index = $index + 1; ?>
        <?php endforeach ?>
      </div>
          <!-- Newsfeed Common Side Bar Right
          ================================================= -->
    			<div class="col-md-2 static static-featured">
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
  <script>
    function openNav() {
    document.getElementById("mySidenav").style.display = 'block'
  }
    </script>
  <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script type="text/javascript">var dates=<?=json_encode($data['dates'])?>;</script>
    <script type="text/javascript">var __allCompetitions=<?=json_encode($data['competitions'])?>;</script>
    <script type="text/javascript">var __predictionInfo=<?=json_encode($data['predictionInfo'])?>;</script>
    <script type="text/javascript">var __outcomes=<?=json_encode($data['outcomes'])?>;</script>
    <script src="/bet_community/Public/js/prediction.js"></script>
    <script src="/bet_community/Public/js/common-sidebar.js"></script>
    <?php if(isLogin()): ?>
      <script src="/bet_community/Public/js/notification.js"></script>
    <?php endif; ?>
  </body>
</html>
