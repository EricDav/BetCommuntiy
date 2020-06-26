
              <!-- About
              ================================================= -->
              <div class="about-profile">
                <div class="about-content-block">
                  <h4 class="grey"><i class="ion-ios-information-outline icon-in-title"></i>Personal Information</h4>
                  <div class="basic-info-profile">
                      <div>
                        <span><i class="fa fa-user" aria-hidden="true"></i> <?=$data['user'][0]['name']?> <em>  (Full name)</em></span>
                      </div>
                      <div>
                        <span><i class="fa fa-envelope" aria-hidden="true"></i><?=$data['user'][0]['email']?><em>  (Email)</em></span>
                      </div>
                      <div>
                        <span><i style="font-size: 2.6rem; margin-right: 15px;" class="<?=$data['user'][0]['sex'] == 'M' ? 'fa fa-male' : 'fa fa-female'?>" aria-hidden="true"></i><?=$data['user'][0]['sex'] == 'M' ? 'Male' : 'Female'?> <em>  (Gender)</em></span>
                      </div>
                  </div>
                </div>
                <div class="about-content-block">
                  <h4 class="grey"><i class="ion-ios-location-outline icon-in-title"></i>Location</h4>
                  <p>Lagos, Nigeria</p>
                  <div class="google-maps">
                    <div id="map" class="map" style="position: relative; overflow: hidden;"><div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);"><div class="gm-err-container"><div class="gm-err-content"><div class="gm-err-icon"><img src="https://maps.gstatic.com/mapfiles/api-3/images/icon_error.png" draggable="false" style="user-select: none;"></div><div class="gm-err-title">Sorry! Something went wrong.</div><div class="gm-err-message">This page didn't load Google Maps correctly. See the JavaScript console for technical details.</div></div></div></div></div>
                  </div>
                </div>
            </div>
