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
</head>
<body>
<?php include 'Pages/common/Header.php';?>

<div id="page-contents" style="position: relative;">
<?php if (isLogin()): ?>
<!-- Modal start -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div style="display: flex;" class="modal-header">
        <h4 style="width: 95%; font-size: 2rem;" class="modal-title" id="exampleModalLongTitle">Create Prediction Using: </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div id='m-body-id' class="modal-body m-body">
    <ul class="nav nav-tabs ul-navs-tabs">
      <li id="tab-one-id" class="active"><a id="tab-one" data-toggle="tab" href="">Booking Number</a></li>
      <li id="tab-two-id"><a id="tab-two" data-toggle="tab" href="">Fixtures</a></li>
    </ul>
    
      <!-- <div class="">
           <p id="main-error"></p>
           <div style="margin-top: 5px;"><b>First Game Begins: </b></div>
          <div class="flex-input">
            <input id="start-date" type="date" class="form-control">
            <input id="start-time" type="time" class="form-control">
          </div>
          <p id="start" style="color:red; font-style:italic"></p>

          <div style="margin-top: 5px;"><b>Last Game Begins: </b></div>
          <div class="flex-input">
            <input id="end-date" type="date" class="form-control">
            <input id="end-time" type="time" class="form-control">
          </div>
          <p id="end" style="color:red; font-style:italic"></p>

          <div style="margin-top: 5px;"><b>Prediction: </b></div>
          <textarea id="prediction" rows="8"></textarea>
          <p id="predict" style="color:red; font-style:italic"></p>

          <div style="margin-top: 5px;"><b>Total odds: </b></div>
          <input id="total-odds" type="text">
          <p id="odds" style="color:red; font-style:italic"></p>
      </div> -->

      <!-- This section begins displays for the first tab for creating prediction through booking code-->
      <div id="booking-code-tab" id="data-entry" style="margin-top: 15px;">
        <div class="row">
          <div class="col-md-6">
          <div style="margin-top: 5px;"><b>Betting Platform: </b></div>
            <select id="betting-type" type="time" class="form-control">
              <?php foreach($data['supportedBettingPlatforms'] as $platform): ?>
                <option><?=$platform?></option>
              <?php endforeach; ?>
            </select>
          </diV>
          <div class="col-md-6">
            <div style="margin-top: 5px;"><b>Booking Number: </b></div>
            <input id="booking-number" type="text" class="form-control">
          </div>
        </div>
     </div>
    <!-- This section ends displays for the first tab for creating prediction through booking code-->
    
     <div id="fixtures-tab" class="data-entry" style="margin-top: 15px; display: none;">
        <!-- Section table for displaying  selected games-->
        <div id="table-section">

        </div>
        <!-- End Section table for displaying  selected games-->

        <div class="row">
          <div class="col-md-4">
          <div style="margin-top: 5px;"><b>Competition: </b></div>
            <input autocomplete="off" id="competition-search" type="search" class="form-control my-input" placeholder="Search by name, countr">
            <div id="dropdown-competetion" class="dropdown-content">
              <?php foreach($data['competitions'] as $competition): ?>
                  <a onclick="selectCompetition(this)"  id="<?=$competition->id?>"><?=getCompetitionName($competition)?></a>
              <?php endforeach; ?>
            </div>
            <div id="competition-error" class="fixtures-booking-error">Required. You need to select a competition</div>
          </diV>
          <div class="col-md-4">
            <div style="margin-top: 5px;"><b>Fixture: </b></div>
            <input autocomplete="off" id="fixtures-search" type="search" class="form-control my-input" placeholder="Search by team name...">
            <div id="fetching-fixtures">Fetching fixtures...</div>
            <div id="dropdown-fixture" class="dropdown-content">
            </div>
            <div id="fixtures-error" class="fixtures-booking-error">Required. You need to select a fixture.</div>
          </div>

          <div class="col-md-4">
            <div style="margin-top: 5px;"><b>Enter Outcome: </b></div>
            <input id="outcome" type="text" class="form-control my-input" placeholder="e.g X2">
            <div id="dropdown-outcomes" class="dropdown-content">
              <?php foreach($data['outcomes'] as $outcome): ?>
                  <a onclick="selectOutcome(this)"><?=$outcome?></a>
              <?php endforeach; ?>
            </div>
            <div id="outcome-error" class="fixtures-booking-error">Required. You need to enter an outcome.</div>
          </div>
        </div>
        <button id="add-game" type="button" class="btn btn-primary">Add</button>
     </div>
    </div>
      <div class="modal-footer">
        <button id="prediction-submit" type="button" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
<!-- Model close -->

    <!-- Model start of delete confirmation -->
       <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
          <span class="delete-close-icon" id="close-icon"><i class="fa fa-close"></i></span>
          <div style="height: 50px;" id='m-body-id' class="modal-body m-body">
             <div style="margin-top: 50px;" class="obs-msg">Are you sure you want to delete this prediction?</div><br>
             <div id="login-modal-txt" style="margin-top: -20px; font-style: oblique;" class="obs-msg"></div>
             <span id="follow-note"></span>
          </div>
          <div class="modal-footer">
            <a id="delete-cancel" style="margin-right: 20px;">Cancel</a>
            <button style="background-color: red; width: 150px;" id="delete-prediction" type="button" class="btn btn-primary">Yes Delete</button>
          </div>
        </div>
      </div>
    </div>
<?php endif; ?>

  <?php if (!isLogin()): ?>
      <!-- Modal start -->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
          <span id="close-icon"><i class="fa fa-close"></i></span>
          <div style="height: 220px;" id='m-body-id' class="modal-body m-body">
             <div style="margin-top: 50px;" class="obs-msg">Sorry for the obstruction!</div><br>
             <div id="login-modal-txt" style="margin-top: -20px; font-style: oblique;" class="obs-msg"></div>
             <span id="follow-note"></span>
             <div class="login-modal-button-wrapper col-md-12">
              <button style="width: 80%;" id="prediction-login" type="button" class="btn btn-primary">Login/Sign up</button><br>
            </div>
          </div>
        </div>
      </div>
    </div>
      <!-- Model close -->
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
                    <?=$data['paginationHtml']?>
                </div>
            		<div  class="col-md-5 col-sm-9">
                    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#exampleModal" id="open-prediction-modal">Create Prediction</button>
                </div>
            	</div>
            </div>
            <!-- Post Create Box End-->

          <!-- Post Content
          ================================================= -->
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
                <a class="dropdown-item"><i class="fa fa-bug"></i> Report Prediction</a>
                <div class="line-divider"></div>
                <a class="dropdown-item"><i class="fa fa-share-alt"></i> Copy Prediction Link</a>
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
  <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script type="text/javascript">var dates=<?=json_encode($data['dates'])?>;</script>
    <script type="text/javascript">var __allCompetitions=<?=json_encode($data['competitions'])?>;</script>
    <script type="text/javascript">var __predictionInfo=<?=json_encode($data['predictionInfo'])?>;</script>
    <script type="text/javascript">var __outcomes=<?=json_encode($data['outcomes'])?>;</script>
    <script src="/bet_community/Public/js/prediction.js"></script>
    <script src="/bet_community/Public/js/common-sidebar.js"></script>
  </body>
</html>
