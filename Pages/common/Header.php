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
            <a class="navbar-brand" href="index-register.html"><img src="images/logo.png" alt="logo"></a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right main-menu">
              <li class="dropdown">
                <a href="/" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Home</a>
              </li>
              <li class="dropdown">
                <a href="/about" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About Us</a>
              </li>
              <li class="dropdown"><a href="/contact">Contact</a></li>
              <?php if (!$data['isLogin']): ?>
                <li class="dropdown"><a href="/login">Login</a></li>
              <?php endif ?>

              <?php if ($data['isLogin']): ?>
                <li class="dropdown"><a href="/my-profile">My Profile</a></li>
                <li class="dropdown"><a href="/my-profile"><i style="font-size: 2rem; margin-top: 2px;" class="fa fa-bell"></i></a></li>
                <li class="dropdown"><a href="/my-profile">Logout</a></li>
              <?php endif ?>

            </ul>
            <form class="navbar-form navbar-right hidden-sm">
              <div class="form-group">
                <i class="icon ion-android-search"></i>
                <input type="text" class="form-control" placeholder="Search friends, photos, videos">
              </div>
            </form>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
      </nav>
 </header>
