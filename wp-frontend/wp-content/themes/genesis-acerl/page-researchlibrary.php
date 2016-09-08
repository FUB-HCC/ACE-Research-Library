<html ng-app="researchLibrary">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angularjs-slider/5.5.0/rzslider.min.css">
    <link rel="stylesheet" href="<?= get_stylesheet_directory_uri() ?>/../genesis/style.css">
    <link rel="stylesheet" href="<?= get_stylesheet_directory_uri() ?>/style.css">

    <script type='text/javascript' src='../wp-includes/js/jquery/jquery.js?ver=1.12.4'></script>
    <script type='text/javascript' src='../wp-includes/js/jquery/jquery-migrate.min.js?ver=1.4.1'></script>

    <script src="https://cdn.jsdelivr.net/lodash/3.10.0/lodash.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-animate.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-aria.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-messages.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-route.js"></script>

    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.0.0/ui-bootstrap.min.js?ver=4.5.3'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.0.0/ui-bootstrap-tpls.min.js"></script>

    <script src="<?= get_stylesheet_directory_uri() ?>/src/angularjs-dropdown/angularjs-dropdown-multiselect.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/angularjs-slider/5.5.0/rzslider.min.js"></script>

    <!-- Angular Material Javascript using GitCDN to load directly from `bower-material/master` -->
    <script src="https://gitcdn.link/repo/angular/bower-material/master/angular-material.js"></script>

    <script src="<?= get_stylesheet_directory_uri() ?>/js/config_app.js"></script>
    <script src="<?= get_stylesheet_directory_uri() ?>/js/route.js"></script>
    <script src="<?= get_stylesheet_directory_uri() ?>/js/controller/controller.js"></script>
    <script src="<?= get_stylesheet_directory_uri() ?>/js/controller/controller_search.js"></script>
    <script src="<?= get_stylesheet_directory_uri() ?>/js/services/db.js"></script>
</head>
<body>

    <div id="all">
        <div ng-view></div>
    </div>

  </body>
</html>
