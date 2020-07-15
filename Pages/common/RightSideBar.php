<div id="f-users-wrapper" class="col-md-2 static static-featured">
            <div class="suggestions is_stuck" id="sticky-sidebar" style="position: fixed; top: -3px; width: 200px;">
              <h4 class="grey"><b>Featured Users</b></h4>
              <?php foreach($data['featuredUsers'] as $featuredUser): ?>
                <div class="follow-user">
                  <img src="<?=BetCommunity::IMAGES_PATH . $featuredUser['image_path']?>" alt="" class="profile-photo-sm pull-left">
                  <div>
                    <h5><a href="<?='/users/profile?id=' . (string)(BetCommunity::DEFAULT_ADD_PROFILE + $featuredUser['id']) ?>"><?=$featuredUser['name']?></a></h5>
                    <a onclick="followUser(2, <?=$featuredUser['id']?>)" id="<?=$featuredUser['id']?>" class="text-green"><?=$controllerObject->isFollowing($featuredUser['id']) ? 'Following': 'Follow' ?></a>
                  </div>
                </div>
              <?php endforeach ?>
              </div>
              <div style="position: static; width: 155px; height: 374px; display: block; vertical-align: baseline; float: none;">
            </div>
    </div>
</div>
