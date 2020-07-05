<div class="modal fade" id="reportBugModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <div style="display: flex; margin-bottom: -20px; border-bottom: unset;" class="modal-header">
            <h4 style="width: 95%; font-size: 2rem; margin-left: -10px;" class="modal-title" id="exampleModalLongTitle">Report Bug/Error </h4>
            <button style="margin-left: 50px; font-size: 30px;" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          </div>
          <div style="margin-top: 0px;" class="modal-body">
            <div class="form-group">
              <label>Please Select a problem</label>
              <select id="problem" class="form-control" id="exampleFormControlSelect1">
                <option>--- Select a Problem ---</option>
                <?php foreach(BetCommunity::BUGS as $bug): ?>
                  <option><?=$bug?></option>
                <?php  endforeach ?>
              </select>
            </div>
            <div class="form-group">
              <label>Extra note</label>
              <input id="extra-note" type="text" class="form-control" placeholder="">
            </div>
          </div>
          <div class="modal-footer">
            <button id="report-bug" type="button" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>
</div>
