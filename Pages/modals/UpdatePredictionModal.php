<div class="modal fade" id="updatePredictionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div style="display: flex;" class="modal-header">
        <i data-dismiss="modal"  id="arrow-back" class="fa fa-arrow-left" aria-hidden="true"></i>
        <h4 style="width: 95%; font-size: 2rem;" class="modal-title" id="exampleModalLongTitle">Update Prediction: </h4>
        <button id="cancel-create-prediction-button" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div style="margin-top: 0px;" id='m-body-id' class="modal-body m-body">
      <p style="color: red; font-style: oblique;"  id="main-error-update"></p>
      <div id="outcome-success-update" style="display: none; padding: 5px !important;" class="alert alert-success">
            <span id="outcome-response-message-update"></span>
      </div>
    
     <div id="fixtures-tab" class="data-entry" style="margin-top: 15px;">
        <!-- Section table for displaying  selected games-->
        <div id="table-prediction-update">

        </div>
        <!-- End Section table for displaying  selected games-->
     </div>

    </div>
      <div class="modal-footer">
        <button id="update-prediction" type="button" class="btn btn-primary">Update</button>
      </div>
    </div>
  </div>
</div>
<!-- Model close -->