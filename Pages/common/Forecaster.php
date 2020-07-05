<div class="friend-list">
    <div class="row">
        <?php $forcasters = isset($data['forcasters']) ? $data['forcasters'] : $data['followers'] ?>
        <?php foreach($forcasters as $forcaster): ?>
            <div class="col-md-6 col-sm-6">
                 <div class="friend-card">
                  	<img src="/bet_community/Public/images/covers/champions.jpg" alt="profile-cover" class="img-responsive cover">
                  	<div class="card-info">
                      <img style="object-fit: cover;" src="<?=BetCommunity::IMAGES_PATH . $forcaster['image_path']?>" alt="user" class="profile-photo-lg">
                      <div class="friend-info">
                        <?php $id= isset($data['forcasters']) ? $forcaster['id'] : $forcaster['follower_id']?>

                        <?php if(isLogin() && $_SESSION['userInfo']['id'] != $id): ?>
                            <a onclick="followUser(2, <?=$id?>)" id="<?='forecaster-follow-' . $id?>" class="pull-right text-green"><?=isLogin() && $controllerObject->isFollowing($id) ? 'Following': 'Follow' ?></a>
                        <?php endif ?>

                      	<h5><a href="<?='/users/profile?id=' . (string)(BetCommunity::DEFAULT_ADD_PROFILE + $id) ?>" class="profile-link"><?=$forcaster['name']?></a></h5>
                        <p>Total Predictions: <b><?=$forcaster['total_predictions']?></b></p>
                        <span>Correct Predictions: <b><?=$forcaster['total_predictions_won']?> </b><i style="color: green;" class="fa fa-check"></i></span>
                    </div>
                </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
