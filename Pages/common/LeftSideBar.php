<ul class="nav-news-feed">
    <li><i class="fa fa-futbol-o"></i><div><a href="/"><?=$controllerObject->formatFilterText('All Predictions')?></a></div></li>
    <li><i class="fa fa-user"></i><div><a href="newsfeed-people-nearby.html">Admin Predictions</a></div></li>
    <li><i class="icon ion-ios-people-outline"></i><div><a id="forecasters" href="/forcasters">Forecasters</a></div></li>
    <li><i style="color: green;" class="fa fa-check"></i><div style="color: black"><a id="correct-prediction"><?=$controllerObject->formatFilterText('Correct Predictions')?></a></div></li>
    <li><i class="fa fa-calendar-o"></i><div><a id="today-prediction"><?=$controllerObject->formatFilterText('Today Predictions')?></a></div></li>
    <li><i class="fa fa-calendar-check-o"></i><div><a id="yesterday-prediction"><?=$controllerObject->formatFilterText('Yesterday Predictions')?></a></div></li>
</ul><!--news-feed links ends-->
<div id="chat-block" class="" style="">
    <label class="odds-label">Min Odds</label>
    <input id="min_odd"  type="text" class="form-control" value="">
    <p id="min-error-text" class="odd_error"></p>

    <label class="odds-label">Max Odds</label>
    <input id="max_odd"  type="text" class="form-control" value="">
    <p id="max-error-text" class="odd_error"></p>
    <div class="title">Search Odd</div>
</div><!--chat block ends-->
