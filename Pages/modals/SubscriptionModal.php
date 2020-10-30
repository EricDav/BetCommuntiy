<div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <style>
            .free-paid {
                display: flex;
                font-weight: 600;
                margin-left: 15px;
            }

            .header {
                font-size: 16px;
            }

            .stat {
                display: flex;
                flex-direction: column;
            }
            .sub-free-paid {
                font-weight: 600;
            }
            .stat-elem {
                font-weight: 500;
                font-style: oblique;
                margin-left: 30px;
            }
        </style>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <div style="display: flex; margin-bottom: -20px; border-bottom: unset;" class="modal-header">
            <h4 style="width: 95%; font-size: 2rem; margin-left: -10px;" class="modal-title" id="exampleModalLongTitle">David's Subscription Plan</h4>
            <button style="margin-left: 50px; font-size: 30px;" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          </div>
          <div style="margin-top: 0px; margin-left: 5px; font-size: 13px;" class="modal-body">
            <div>
                <div>
                    <span class="header"><b>Rate:</b></span><br>
                    <span style="margin-left: 15px;">₦200/Week</span><br>

                    <span class="header"><b>Description:</b></span><br> 
                    <div style="margin-left: 15px;">Receive prediction of odds ranging from 5 to 10odds from 
                        David on Monday, Tuesday and Friday at the rate of ₦200 weekly.
                    </div>

                    <span class="header"><b>Stat:</b></span><br>
                    <div class="stat">
                        <div class="free-paid">
                            <div class="sub-free-paid">Free predictions - </div>
                            <div class="stat-elem">Total: 86</div>
                            <div class="stat-elem">Lost: 86</div>
                            <div class="stat-elem">Won: 86</div>
                        </div>

                        <div class="free-paid">
                            <div class="sub-free-paid">Paid predictions - </div>
                            <div class="stat-elem">Total: 86</div>
                            <div class="stat-elem">Lost: 86</div>
                            <div class="stat-elem">Won: 86</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Subscribe For</label>
                        <select id="problem" class="form-control" id="exampleFormControlSelect1">
                            <option value="1">1 Week</option>
                            <option value="2">2 Weeks</option>
                            <option value="3">3 Weeks</option>
                            <option value="4">4 Weeks</option>
                            <option value="5">5 Weeks</option>
                        </select>
                    </div>
                </div>

            </div>
          </div>
          <div class="modal-footer">
            <button id="report-bug" type="button" class="btn btn-primary">Subscribe</button>
          </div>
        </div>
      </div>
</div>
