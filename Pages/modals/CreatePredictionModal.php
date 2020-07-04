<div class="modal fade" id="createPredictionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div style="display: flex;" class="modal-header">
        <h4 style="width: 95%; font-size: 2rem;" class="modal-title" id="exampleModalLongTitle">Create Prediction Using: </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div id='m-body-id' class="modal-body m-body">
    <p style="color: red; font-style: oblique;"  id="main-error"></p>
    <ul class="nav nav-tabs ul-navs-tabs">
      <li id="tab-one-id" class="active"><a id="tab-one" data-toggle="tab" href="">Booking Number</a></li>
      <li id="tab-two-id"><a id="tab-two" data-toggle="tab" href="">Fixtures</a></li>
    </ul>
  
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