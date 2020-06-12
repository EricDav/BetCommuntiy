<html class="js sizes customelements history pointerevents postmessage webgl websockets cssanimations csscolumns csscolumns-width csscolumns-span csscolumns-fill csscolumns-gap csscolumns-rule csscolumns-rulecolor csscolumns-rulestyle csscolumns-rulewidth csscolumns-breakbefore csscolumns-breakafter csscolumns-breakinside flexbox picture srcset webworkers" lang="en">
<head>
  <?php include 'Pages/common/Head.php'; ?>
  <link rel="stylesheet" href="/bet_community/Public/css/home.css">
</head>
<body>
<?php include 'Pages/common/Header.php';?>

<div id="page-contents" style="position: relative;">
<!-- Modal start -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLongTitle">Create Prediction</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
      <div class="">
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
      </div>
    </div>
      <div class="modal-footer">
        <button id="prediction-submit" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- Model close -->
    	<div class="container">
    		<div class="row">

          <!-- Newsfeed Common Side Bar Left
          ================================================= -->
    			<div class="col-md-3 static">

            <ul class="nav-news-feed">
              <li><i class="fa fa-futbol-o"></i><div><a href="/"><?=$controllerObject->formatFilterText('Predictions', $data['homeNum'])?></a></div></li>
              <li><i class="fa fa-user"></i><div><a href="newsfeed-people-nearby.html">Admin Predictions</a></div></li>
              <li><i class="icon ion-ios-people-outline"></i><div><a href="newsfeed-friends.html">Forcasters</a></div></li>
              <li><i style="color: green;" class="fa fa-check"></i><div style="color: black"><a href="<?='/?filter_option=' . $data['predictionWonQuery']?>"><?=$controllerObject->formatFilterText('Correct Predictions', $data['correctNum'])?></a></div></li>
              <li><i style="color: red;" class="fa fa-close"></i><div><a href="<?='/?filter_option=' . $data['predictionLostQuery']?>"><?=$controllerObject->formatFilterText('Lost Predictions', $data['lostNum'])?></a></div></li>
              <li><i class="fa fa-spinner"></i><div><a href="<?='/?filter_option=' . $data['predictionInprogressQuery']?>"><?=$controllerObject->formatFilterText('Predictions In-progress', $data['inprogressNum'])?></a></div></li>
            </ul><!--news-feed links ends-->
            <div id="chat-block" class="" style="">
              
              <label class="odds-label">Min Odds</label>
              <input id="min_odd"  type="text" class="form-control" value="<?=$data['min']?>">
              <p id="min-error-text" class="odd_error"></p>

              <label class="odds-label">Max Odds</label>
              <input id="max_odd"  type="text" class="form-control" value="<?=$data['max']?>">
              <p id="max-error-text" class="odd_error"></p>

              <div class="title">Search Odd</div>
            </div><!--chat block ends-->
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
          <?php foreach($data['predictions'] as $prediction): ?>
            <div class="post-content">
              <div class="post-container">
              <img src="<?=$prediction['image_path']?>" alt="user" class="profile-photo-md pull-left">
                <div class="post-detail">
                  <div class="user-info">
                    <h5><a href="timeline.html" class="profile-link"><?=$prediction['name']?></a> <a style="cursor:pointer;" class="following"><?=$controllerObject->isFollowing($prediction['user_id']) ? 'unfellow': 'Follow' ?></a></h5>
                    <p id="<?='date-'.$prediction['id']?>" class="text-muted"></p>
                  </div>
                  <div class="reaction">
                    <a class="btn text-green"><i class="icon ion-thumbsup"></i> 2</a>
                  </div>
                  <div class="line-divider"></div>
                  <div class="post-text">
                    <p><?=$prediction['prediction']?></p>
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
                    <a id="<?=$featuredUser['id']?>" href="#" class="text-green"><?=$controllerObject->isFollowing($prediction['user_id']) ? 'Following': 'Follow' ?></a>
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
<script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-database.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-analytics.js"></script>

<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyCQ7OffeQuUOQlD31h3D0J6EE9kQnXXHvc",
    authDomain: "betcommunity-7fb66.firebaseapp.com",
    databaseURL: "https://betcommunity-7fb66.firebaseio.com",
    projectId: "betcommunity-7fb66",
    storageBucket: "betcommunity-7fb66.appspot.com",
    messagingSenderId: "330744767541",
    appId: "1:330744767541:web:028895b50835bfcc433d7c",
    measurementId: "G-KTZTRR4ESW"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
</script>
    <script type="text/javascript">var dates=<?=json_encode($data['dates'])?>;</script>
    <script src="/bet_community/Public/js/prediction.js"></script>
  </body>
</html>
