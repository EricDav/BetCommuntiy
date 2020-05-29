<html>
    <head>
        <title>404: Page Not Found</title>
        <?php
            /**
             * include head styles
             */
            include 'Pages/common/Head.php';
        ?>
    </head>
    <body>
        <section>
            <div class="error-page">
                <div class="error-content">
                    <div class="container">
                        <img src="images/404.png" alt="" class="img-responsive">
                        <div class="error-message">
                            <h1>
                                <span>4</span>
                                <i style = 'font-size: 100px' class = 'icon icon ion-bug'></i>
                                <span>4</span>
                            </h1>
                            <h1 class="error-title">Whoops!</h1>
                            <p>Looks like you are lost. But don't worry there is plenty to see!!</p>
                        </div>
                        <form class="search-form">
                            <div class="form-group">
                                <label for="search_content">Search Content!</label>
                                <input id="search_content" type="text" class="form-control" value="" placeholder="Search what you want to find...">
                            </div>
                            <button type="submit" class="btn btn-primary">Search Now!</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
