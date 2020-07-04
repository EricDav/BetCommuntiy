<html class="js sizes customelements history pointerevents postmessage webgl websockets cssanimations csscolumns csscolumns-width csscolumns-span csscolumns-fill csscolumns-gap csscolumns-rule csscolumns-rulecolor csscolumns-rulestyle csscolumns-rulewidth csscolumns-breakbefore csscolumns-breakafter csscolumns-breakinside flexbox picture srcset webworkers" lang="en">
<head>
  <?php include 'Pages/common/Head.php'?>
  <link rel="stylesheet" href="/bet_community/Public/css/home.css">
  <link rel="stylesheet" href="/bet_community/Public/css/profile.css">
</head>
<body>
<?php include 'Pages/common/Header.php';?>
<div class="container">

   <!-- Modal start for uploading profile image-->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Upload Profile Photo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div style="display: flex; justify-content: center;"  class="modal-body">
              <image style="width: 280px; height: 280px; object-fit: cover;" id="image-preview" src="" alt="" class="img-responsive profile-photo"/>
            </div>
            <div class="modal-footer">
              <button id="upload-photo" type="button"  style="width: 150px" class="btn btn-primary">Upload</button>
            </div>
          </div>
        </div>
      </div>
    <!-- Modal end for uploading profile image-->

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

    <div class="timeline">
        <div class="timeline-cover">
          <!--Timeline Menu for Large Screens-->
          <div class="timeline-nav-bar hidden-sm hidden-xs">
            <div class="row">
              <div class="col-md-3">
                <div class="profile-info">
                  <div class=" overlay-image _b1 ">
                    <img style="object-fit: cover;" id="profile-picture" src="<?=BetCommunity::IMAGES_PATH . $data['user'][0]['image_path']?>" alt="" class="image _b2 img-responsive profile-photo">
                    <?php if ($data['isSelf']): ?>
                      <div class="hover _b3">
                        <div class=" text _2 ">Change Profile Picture</div>
                      </div>
                    <?php endif ?>
                  </div>
                  
                  <?php if ($data['isSelf']): ?>
                    <input style="display: none;" type="file" name="my_file" id="my-file" accept="image/*">
                  <?php endif ?>

                  <h3><?=$data['user'][0]['name']?></h3>
                  <p class="text-muted">Creative Director</p>
                </div>
              </div>
              <div class="col-md-9">
                <ul class="list-inline profile-menu">
                  <li><a id="profile-predictions" class="active">Predictions</a></li>
                  <li><a id="profile-about">About</a></li>
                  <li><a id="profile-followers">Followers<span data="<?=$data['user'][0]['num_followers']?>" id="num-followers">(<?=$data['user'][0]['num_followers']?>)</a></li>
                  <!-- <li><a>Packages</a></li> -->
                </ul>
                <ul class="follow-me list-inline">
                  <?php if (!$data['isSelf']): ?>
                    <li><button id="profile-follow" class="btn-primary"><?=isLogin() && $data['isFollowing'] ? 'Unfollow' : 'Follow' ?></button></li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
          </div>
           <!--Timeline Menu for Large Screens End-->

                  <!--Timeline Menu for Small Screens-->
                    <div class="navbar-mobile hidden-lg hidden-md">
                      <div class="profile-info">
                        <img id="profile-picture" style="object-fit: cover;" src="<?=BetCommunity::IMAGES_PATH . $data['user'][0]['image_path']?>" alt="" class="img-responsive profile-photo _b3">
                        <?php if ($data['isSelf']): ?>
                          <input style="display: none;" type="file" name="my_file" id="my-file" accept="image/*">
                        <?php endif ?>

                        <h4><?=$data['user'][0]['name']?></h4>
                        <p class="text-muted">Creative Director</p>
                      </div>
                      <div class="mobile-menu">
                        <ul class="list-inline">
                          <li id="profile-predictions-mobile" class="active"><a>Predictions</a></li>
                          <li id="profile-about-mobile"><a>About</a></li>
                          <li id="profile-followers-mobile"><a>Followers<span data="<?=$data['user'][0]['num_followers']?>" id="num-followers-mobile">(<?=$data['user'][0]['num_followers']?>)</a></li>
                          <!-- <li><a>Packages</a></li> -->
                        </ul>
                        <?php if (!$data['isSelf']): ?>
                          <button id="profile-follow-mobile" class="btn-primary"><?= isLogin() && $data['isFollowing'] ? 'Unfollow' : 'Follow' ?></button>
                        <?php endif; ?>
                      </div>
                    </div>
                    <!--Timeline Menu for Small Screens End-->
          </div>
  
          
        <div id="page-contents" style="position: relative;">
          <div class="row">
            <div class="col-md-3">
              <div style="display: none;" id="edit-profile-side-bar">
                <?php if ($data['isSelf']): ?>
                  <?php include 'Pages/common/EditProfileSideBar.php'; ?>
                <?php endif ?>
              </div>
            </div>
          <div class="col-md-7">
            <div style="display: none" id="profile-about-wrapper">
              <?php if (!$data['isSelf']): ?>
                <?php include 'Pages/common/AboutProfile-Visitor.php'; ?>
              <?php endif ?>
              <?php if ($data['isSelf']): ?>
                  <div id="edit-profile">
                    <?php include 'Pages/common/EditProfile.php'; ?>
                  </div>
                  <div style="display:none;" id="account-settings">
                    <?php include 'Pages/common/AccountSettings.php'; ?>
                  </div>
                  <div style="display:none;" id="change-password">
                    <?php include 'Pages/common/ChangePassword.php'; ?>
                  </div>
              <?php endif ?>
            </div>

            <div id="followers-wrapper" style="display: none">
                <?php if (sizeof($data['followers']) == 0): ?>
                  <div class="no-prediction"><?=$data['user'][0]['name'] . ' do not have any followers yet' ?></div>
                <?php endif ?>

                <?php if (sizeof($data['followers'] > 0)): ?>
                  <?php include 'Pages/common/Forecaster.php'; ?>
                <?php endif ?>
            </div>
              <!-- Post Create Box
              ================================================= -->
            <div id="profile-prediction-wrapper">
                  <div class="create-post">
                    <div class="row">
                      <div class="col-md-7 col-sm-7">
                        
                      </div>
                      <div  class="col-md-5 col-sm-9">
                          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#exampleModal" id="open-prediction-modal">Create Prediction</button>
                      </div>
                    </div>
                  </div>
              <!-- Post Create Box End-->

              <!-- Post Content
              ================================================= -->
              <?php if(sizeof($data['predictions']) == 0): ?>
                <div class="no-prediction"> No Predictions Found </div>
              <?php endif ?>


              <?php foreach($data['predictions'] as $prediction): ?>
                <?php $isFollowing = $data['isLogin'] && $controllerObject->isFollowing($prediction['user_id']); ?>
                <div class="post-content">
                <div class="post-date hidden-xs hidden-sm">
                      <h5><?=$prediction['name']?></h5>
                      <p class="text-grey">Sometimes ago</p>
                  </div>
                <div class="dropdown dot-menu">
                  <i class="fa fa-ellipsis-h dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                  <div id="menu-action" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <?php if($data['isLogin'] && (int)$_SESSION['userInfo']['role'] > 1): ?>
                      <a class="dropdown-item"> <i class="fa fa-user"></i>  Action</a>
                    <?php endif ?>

                    <a  id="<?= 'dot-menu-' . $prediction['user_id']?>" class="dropdown-item"> <i class="<?= $isFollowing ? 'fa fa-user-times' : 'fa fa-user'; ?>"></i>
                      <?= !$isFollowing ? '  Follow     ' . explode(' ', $prediction['name'])[0] : '  Unfollow     ' . explode(' ', $prediction['name'])[0]?>
                    </a>
                    <div class="line-divider"></div>
                    <a class="dropdown-item"><i class="fa fa-bug"></i> Report Prediction</a>
                    <div class="line-divider"></div>
                    <a class="dropdown-item"><i class="fa fa-share-alt"></i> Copy Prediction Link</a>
                  </div>
                </div>
                  <div style="padding-top: 10px;" class="post-container">
                    <div class="post-detail">
                      <div class="user-info">
                        <h5>
                          <a href="timeline.html" class="profile-link"><?=$prediction['name']?></a>
                          <?php if (!$data['isLogin'] || ($data['isLogin'] && $prediction['user_id'] != $_SESSION['userInfo']['id'])): ?>
                            <a id="<?='follow-' . $prediction['user_id']?>" style="<?= $isFollowing ?  'cursor:default;' : 'cursor:pointer;' ?>" class="following"><?=$isFollowing ? 'Following': 'Follow' ?></a>
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
            <?php endforeach ?>
              <!-- Post Content
            ================================================= -->
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
    <?php  include 'Pages/common/Footer.php';?> 
    <div id="spinner-wrapper" style="display: none;">
      <div class="spinner"></div>
    </div>
    <?php include 'Pages/common/Script.php'?>
    <script type="text/javascript">var dates=<?=json_encode($data['dates'])?>;</script>
    <script type="text/javascript">var __userId=<?=json_encode($data['user'][0]['id'])?>;</script>
    <script type="text/javascript">var __name=<?=json_encode($data['user'][0]['name'])?>;</script>
    <script type="text/javascript">var __allCompetitions=<?=json_encode($data['competitions'])?>;</script>
    <script type="text/javascript">var __predictionInfo=<?=json_encode($data['predictionInfo'])?>;</script>
    <script type="text/javascript">var __outcomes=<?=json_encode($data['outcomes'])?>;</script>
    <script type="text/javascript">var isFollowing=<?=json_encode($data['isFollowing'])?>;</script>
    <script src="/bet_community/Public/js/prediction.js"></script>
    <script src="/bet_community/Public/js/profile.js"></script>
    <script src="/bet_community/Public/js/follow.js"></script>
</body>
<body>
