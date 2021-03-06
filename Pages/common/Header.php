<header id="header">
      <nav class="navbar navbar-default navbar-fixed-top menu">
        <div class="container">

          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a  class="navbar-brand" href="index-register.html"><img style="width: 200; margin-top: -10px; height: 40; border-radius: 10px;" src="/bet_community/Public/images/logo1.png" alt="logo"></a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right main-menu">
              <li class="dropdown">
                <a href="/">Home</a>
              </li>
              <li class="dropdown">
                <a href="/about">About Us</a>
              </li>
              <li class="dropdown">
                <a href="/benefits">Benefits</a>
              </li>
              <li class="dropdown"><a href="/contact">Contact</a></li>
              <?php if (!isLogin()): ?>
                <li class="dropdown"><a href="/login">Login</a></li>
              <?php endif ?>


              <?php if (isLogin()): ?>
                <li class="dropdown">
                  <a style="margin-top: -2px;" href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span style="margin-right: 5px;"><img id="header-image" style="width: 20; height: 20; border-radius: 10px; object-fit: cover;" src="<?=BetCommunity::IMAGES_PATH . $request->session['userInfo']['imagePath']?>" alt=""></span>My Profile
                  </a>
                  <ul class="dropdown-menu newsfeed-home">
                    <li><a href="<?='/users/profile?id=' . (string)(BetCommunity::DEFAULT_ADD_PROFILE + (int)$_SESSION['userInfo']['id']) ?>">View Profile</a></li>
                    <li><a href="/logout">Logout</a></li>
                  </ul>
                </li>
                <li id="" class="dropdown"><a><i style="font-size: 2rem; margin-top: 2px;" class="fa fa-bell"></i></a></li>
                <li id="notification-bell"><a style="color: #fff;margin-top: 10px;width: fit-content;"><span id="unseen-notification" style="display: inline-block;margin-top: -17;text-align: center;height: 22px;width: 22px;border-radius: 50%;font-size: 12px;"></span></a></li>
              <?php endif ?>
            </ul>
            <!-- <form class="navbar-form navbar-right hidden-sm">
              <div class="form-group">
                <i class="icon ion-android-search"></i>
                <input type="text" class="form-control" placeholder="Search friends, photos, videos">
              </div>
            </form> -->
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
      </nav>
 </header>
 <?php if (isLogin()): ?>
    <!-- Obstruction modal -->
    <?php  include 'Pages/common/Notifications.php';?>
  <?php endif; ?>
