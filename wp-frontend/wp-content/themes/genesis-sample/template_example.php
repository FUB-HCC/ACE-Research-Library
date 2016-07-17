<?php /* Template Name: Example */ ?>
<html ng-app="researchLibrary">
  <head>
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>../../genesis-sample/src/bootstrap-3.3.6/css/bootstrap.css">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->

   <?php
    get_header();
   ?>

   <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>../../genesis-sample/src/library.css">
 </head>

 <body>
 <script src="<?php bloginfo('template_url'); ?>../../genesis-sample/src/bootstrap-3.3.6/js/collapse.js"></script>

 <script src="https://cdn.jsdelivr.net/lodash/3.10.0/lodash.min.js"></script>

 <script src="  https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-animate.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-aria.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-messages.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-route.js"></script>
 <script src="<?php bloginfo('template_url'); ?>../../genesis-sample/src/ui-bootstrap-tpls-1.3.2.min.js"></script>
 <script src="<?php bloginfo('template_url'); ?>../../genesis-sample/src/angularjs-dropdown/angularjs-dropdown-multiselect.js"></script>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

 <!-- Angular Material Javascript using GitCDN to load directly from `bower-material/master` -->
  <script src="https://gitcdn.link/repo/angular/bower-material/master/angular-material.js"></script>

  <script src="<?php bloginfo('template_url'); ?>../../genesis-sample/js/config_app.js"></script>
  <script src="<?php bloginfo('template_url'); ?>../../genesis-sample/js/route.js"></script>
  <script src="<?php bloginfo('template_url'); ?>../../genesis-sample/js/controller/controller.js"></script>
  <script src="<?php bloginfo('template_url'); ?>../../genesis-sample/js/controller/controller_search.js"></script>
  <script src="<?php bloginfo('template_url'); ?>../../genesis-sample/js/services/db.js"></script>

  <div id="all">
	  <div ng-view></div>
  </div>
  </body>
</html>
