<div class="edit-profile-container">
                <div class="block-title">
                  <h4 class="grey"><i class="icon ion-ios-settings"></i>Account Settings</h4>
                  <div class="line"></div>
                </div>
                <div class="edit-block">
                  <div class="settings-block">
                    <div class="row">
                      <div class="col-sm-9">
                        <div class="switch-description">
                          <div><strong>Send me notifications</strong></div>
                          <p>Send me notification emails when any forecaster I am following post a prediction</p>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="toggle-switch">
                          <label class="switch">
                          <?php if($data['user'][0]['send_email_notification']): ?>
                            <input id="email-notification-settings" type="checkbox" checked="">
                          <?php endif; ?>
                            <?php if(!$data['user'][0]['send_email_notification']): ?>
                              <input id="email-notification-settings" type="checkbox">
                            <?php endif; ?>
                            <span class="slider round"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="line"></div>
                  <!-- <div class="settings-block">
                    <div class="row">
                      <div class="col-sm-9">
                        <div class="switch-description">
                          <div><strong>Text messages</strong></div>
                          <p>Send me messages to my cell phone</p>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="toggle-switch">
                          <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div> -->
          </div>
    </div>
    