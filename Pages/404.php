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
        <?php
            include 'Pages/common/Header.php';
        ?>
        <section>
            <div class="error-page">
                <div class="error-content">
                    <div class="container">
                        <img src="/bet_community/Public/images/404.png" alt="" class="img-responsive">
                        <div class="error-message">
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
        <?php
            include 'Pages/common/Footer.php';
        ?>
        <?php include 'Pages/common/Script.php'?>
        <script src="/bet_community/Public/js/script.js"></script>
    </body>
</html>
