<html>
    <head>
        <title>About | Bet Community</title>
        <?php
            /**
             * include head styles
             */
            include 'Pages/common/Head.php';
        ?>
        <link rel="stylesheet" href="/bet_community/Public/css/about.css">
    </head>
    <body>

        <?php
            include 'Pages/common/Header.php';
        ?>
        <div class="about-img">
        <div id="page-contents">
    	    <div class="container">
                <div class="row">
                   <h2 class="about-txt">About Us</h2>
    		    </div>
    	    </div>
        </div>
        </div>
        
        <div class="cover-abt" id="page-contents">
    	    <div class="container">
                <div class="row">
                    <div class="how-to-play">What We Do?</div>
                    <div style="margin-top: 15px;">BetCommunity is a platform that connects betters and forecasters.<br>
                    It is a community of bet  lovers, is platform that helps betters come together and make their predictions. </div>
                   <div style="margin-top: 10px;" class="how-to-play">How to Predict?</div>
                   <div class="header">Booking Code:</div>
                   <div>You can drop your prediction with your booking code. For now the supported betting platform is Bet9ja, BetKing and Sportybet booking codes. You can enter your booking code in the create prediction modal it will populate the matches selected and omit any game that has started. Then you can save the prediction on our platform. The results of the games are automatically updated by our platform.</div>
                   <div class="header">Fixtures:</div>
                   <div>You can make your predictions directly from our platform using the fixtures tab in the create prediction modal.
                        This section you need to select the competition it populate all the fixtures you can search by fixtures e.g “Chelsea vs  Man” it populate the result then you select. Lastly you can choose the outcome of the game. </div>

                 <div style="margin-top: 20px;" class="how-to-play">Rules/Limitation</div>
                    <div style="margin-top: 10px">As a user of our platform you can only make 3 predictions per day. You can not update a prediction after it has been created. But you can delete your own prediction you created before the first game begin. </div>
    		    </div>
    	    </div>
        </div>
        <?php include 'Pages/common/Footer.php';?>
        <?php include 'Pages/common/Script.php'?>
        <script src="/bet_community/Public/js/script.js"></script>
    </body>
</html>
