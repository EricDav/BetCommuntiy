<div class="edit-profile-container">
    <div style="display: none;" class="alert alert-success" role="alert">Changes updated successfully</div>
            <div class="block-title">
                <h4 class="grey"><i class="icon ion-android-checkmark-circle"></i>Edit basic information</h4>
                <div class="line"></div>
                <div class="error-messages" id="edit-profile-error-message"></div>
              </div>
                <div class="edit-block">
                  <form name="basic-info" id="basic-info" class="form-inline" _lpchecked="1">
                    <div class="row">
                      <div class="form-group col-sm-6">
                        <label for="firstname">First name</label>
                        <input id="firstname" class="form-control input-group-lg" type="text" name="firstname" title="Enter first name" placeholder="First name" value="<?=$data['firstName']?>" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAfBJREFUWAntVk1OwkAUZkoDKza4Utm61iP0AqyIDXahN2BjwiHYGU+gizap4QDuegWN7lyCbMSlCQjU7yO0TOlAi6GwgJc0fT/fzPfmzet0crmD7HsFBAvQbrcrw+Gw5fu+AfOYvgylJ4TwCoVCs1ardYTruqfj8fgV5OUMSVVT93VdP9dAzpVvm5wJHZFbg2LQ2pEYOlZ/oiDvwNcsFoseY4PBwMCrhaeCJyKWZU37KOJcYdi27QdhcuuBIb073BvTNL8ln4NeeR6NRi/wxZKQcGurQs5oNhqLshzVTMBewW/LMU3TTNlO0ieTiStjYhUIyi6DAp0xbEdgTt+LE0aCKQw24U4llsCs4ZRJrYopB6RwqnpA1YQ5NGFZ1YQ41Z5S8IQQdP5laEBRJcD4Vj5DEsW2gE6s6g3d/YP/g+BDnT7GNi2qCjTwGd6riBzHaaCEd3Js01vwCPIbmWBRx1nwAN/1ov+/drgFWIlfKpVukyYihtgkXNp4mABK+1GtVr+SBhJDbBIubVw+Cd/TDgKO2DPiN3YUo6y/nDCNEIsqTKH1en2tcwA9FKEItyDi3aIh8Gl1sRrVnSDzNFDJT1bAy5xpOYGn5fP5JuL95ZjMIn1ya7j5dPGfv0A5eAnpZUY3n5jXcoec5J67D9q+VuAPM47D3XaSeL4AAAAASUVORK5CYII=&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: pointer;">
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="lastname" class="">Last name</label>
                        <input id="lastname" class="form-control input-group-lg" type="text" name="lastname" title="Enter last name" placeholder="Last name" value="<?=$data['lastName']?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-xs-12">
                        <label for="email">My email</label>
                        <input id="email" class="form-control input-group-lg" type="text" name="Email" title="Enter Email" placeholder="My Email" value="<?=$data['user'][0]['email']?>">
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-xs-12">
                        <label for="email">My Phone number</label>
                        <input id="phonenumber" class="form-control input-group-lg" type="text" name="phone_number" title="Enter Phone number" placeholder="My Phone number" value="<?=$data['user'][0]['phone_number']?>">
                      </div>
                    </div>

                    <div class="form-group gender">
                      <span class="custom-label"><strong>I am a: </strong></span>
                      <label class="radio-inline">
                          <?= $data['user'][0]['sex'] == 'M' ? '<input id="sex-male" type="radio" name="optradio" checked>' : '<input type="radio" name="optradio">' ?>
                            Male
                      </label>
                      <label class="radio-inline">
                        <?= $data['user'][0]['sex'] == 'F' ? '<input type="radio" name="optradio" checked>' : '<input type="radio" name="optradio">' ?>
                        Female
                      </label>
                    </div>
                    <div class="row">
                      <div class="form-group col-xs-6">
                        <label for="city"> My city</label>
                        <input id="city" class="form-control input-group-lg" type="text" name="city" title="Enter city" placeholder="Your city" value="<?=$data['user'][0]['city']?>">
                      </div>
                      <div class="form-group col-xs-6">
                        <label for="country">My country</label>
                        <select class="form-control" id="country">
                          <option value="country" disabled="" selected="">Country</option>
                          <?php foreach(BetCommunity::countries as $countryCode => $country): ?>
                            <?php if($countryCode == $data['user'][0]['country']): ?>
                                <option value="<?=$countryCode?>" selected><?=$country?></option>
                            <?php endif; ?>
                            <?php if($countryCode != $data['user'][0]['country']): ?>
                                <option value="<?=$countryCode?>"><?=$country?></option>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </form>
                  <button id="submit-update-btn" class="btn btn-primary">Save Changes</button>
            </div>
</div>
        