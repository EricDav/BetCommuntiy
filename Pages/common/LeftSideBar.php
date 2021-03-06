<ul id="chat-block" class="nav-news-feed" style="position: fixed; top: 70px; width: 242.5px;">
    <li><i class="fa fa-futbol-o"></i><div><a href="/"><?=$controllerObject->formatFilterText('All Predictions')?></a></div></li>
    <!-- <li><i class="fa fa-user"></i><div><a href="newsfeed-people-nearby.html">Admin Predictions</a></div></li> -->
    <li><i class="icon ion-ios-people-outline"></i><div><a id="forecasters" href="/forcasters">Forecasters</a></div></li>
    <li><i style="color: green;" class="fa fa-check"></i><div style="color: black"><a id="correct-prediction"><?=$controllerObject->formatFilterText('Correct Predictions')?></a></div></li>
    <li><i class="fa fa-calendar-o"></i><div><a id="today-prediction"><?=$controllerObject->formatFilterText('Today Predictions')?></a></div></li>
    <li><i class="fa fa-calendar-check-o"></i><div><a id="yesterday-prediction"><?=$controllerObject->formatFilterText('Yesterday Predictions')?></a></div></li>
    <?php if (isAdmin()): ?>
        <li><i class="fa fa-clock-o"></i><div><a id="pending-outcomes" href="/predictions/pending-outcomes"><?=$controllerObject->formatFilterText('Pending Predictions Outcomes')?></a></div></li>
        <li><i class="fa fa-check-circle"></i><div><a id="approved-outcomes" href="/predictions/my/approved-outcomes"><?=$controllerObject->formatFilterText('My Approved Outcomes')?></a></div></li>
    <?php endif; ?>
</ul><!--news-feed links ends-->
<!-- <div id="chat-block" class="" >
    <label class="odds-label">Min Odds</label>
    <input id="min_odd"  type="text" class="form-control" value="">
    <p id="min-error-text" class="odd_error"></p>

    <label class="odds-label">Max Odds</label>
    <input id="max_odd"  type="text" class="form-control" value="">
    <p id="max-error-text" class="odd_error"></p>
    <div class="title">Search Odd</div>
</div> -->
<!--chat block ends-->
