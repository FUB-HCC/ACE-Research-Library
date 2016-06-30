<?php /* Template Name: Example */ ?>
<html ng-app="researchLibrary">
  <head>
    
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/src/bootstrap-3.3.6/css/bootstrap.css">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/src/library.css">

    <?php
     get_header();
    ?>
  </head>
  <body ng-controller="mainCtrl as ctrl">
  <script src="<?php bloginfo('template_url'); ?>/src/bootstrap-3.3.6/js/collapse.js"></script>

  <script src="<?php bloginfo('template_url'); ?>/src/angular-1.5.5/angular.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/src/angular-1.5.5/angular-animate.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/src/angular-1.5.5/angular-aria.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/src/angular-1.5.5/angular-messages.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/src/ui-bootstrap-tpls-1.3.2.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

  <!-- Angular Material Javascript using GitCDN to load directly from `bower-material/master` -->
  <script src="https://gitcdn.link/repo/angular/bower-material/master/angular-material.js"></script>

  <script src="<?php bloginfo('template_url'); ?>/js/config_app.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/controller/controller.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/services/db.js"></script>


  <div class="row">
	  <div class="col-md-2 sidebar" ng-show="sidebar_hide">
		  Years
		  <ul>
			  <li ng-repeat="year in filter_frame.years ">
				  <a href="#">{{year}}</a>
			  </li>
		  </ul>
		  <hr class="lib-link-sep" />
		  Category
		  <ul>
			  <li ng-repeat="cat in filter_frame.categories">
				  <a href="#" ng-click="onFilter($index)">{{cat}}</a>
			  </li>
		  </ul>
		  <hr class="lib-link-sep" />
		  Type of publication
		  <ul>
			  <li ng-repeat="pub in filter_frame.publication_type ">
				  <a href="#">{{pub}}</a>
			  </li>
		  </ul>
		  <hr class="lib-link-sep" />
		  Sortorder
		  <hr class="lib-link-sep" />
	  </div>
	  <div id="main" class="col-md-10 container container-fluid typeahead-demo main-container main">
		  <form name="Search" ng-submit="search(searchitem)">
			   
			  <div class="input-group">
				  <input class="form-control" name="searchtext" type="text" ng-model="searchitem" placeholder="Locations loaded via $http"
						 uib-typeahead="paper.value for paper in getPapersSuggest($viewValue) | filter:$viewValue | limitTo:8"
						 typeahead-loading="loadingLocations" typeahead-no-results="noResults" typeahead-on-select="onSelect($item,$model,$label)">
				  <span class="input-group-btn">
					  <button class="btn btn-default" type="button" ng-click="search(searchitem)">
						  <i class="glyphicon glyphicon-search"></i> Search
					  </button>
				  </span>
			  </div>

			  <!--filter:..... ist hier nicht nötig(besonders wenn query im Lauf...)-->
			  <i ng-show="loadingLocations" class="glyphicon glyphicon-refresh"></i>
			  <div ng-show="noResults">
				  <i class="glyphicon glyphicon-remove"></i> No Results Found
			  </div>
		  </form>
		  <div align="right">
			  <a data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
				  Advanced
			  </a>
		  </div>
		  <div class="collapse" id="collapseExample">
			  <div class="well">
				  <div class="row">
					  <div class="col-md-1">
						  Years:
						  <br>
						  Author
					  </div>
					  <div class="col-md-4">
						  <input type="text" style="width: 100px;height: 25;">
						  <input type="text" style="width: 100px;height: 25;">
						  <br>
						  <input type="text" style="width: 200px; height: 25;">
					  </div>
					  <div class="col-md-1">
						  ISDN:
						  <br>
						  State:
					  </div>
					  <div class="col-md-4">
						  <input id="ISDN" type="text" style="width: 150px;height: 25;">
						  <br>
						  <input id="state" type="text" style="width: 150px;height: 25;">
					  </div>
				  </div>
			  </div>
		  </div>
		  <ul>
			  <li ng-repeat="paper in papers">
				  <h4 class="lib-link-name"><a id="link-74" class="track_this_link " ng-title="{{paper.title}}" ng-href="{{paper.url}}" target="_blank" rel="nofollow">{{paper.title}}</a></h4></i>
				  <div>
					  <i> <small><span class="lib-link-citation">
						  {{paper.subtitle}} Authors:
						  <span ng-repeat="author in paper.authors">
							  {{author}},
						  </span>
						  ({{paper.published}}), {{paper.subtitle}}
						  <br>{{paper.publisher}}
					  </span></small>
					  </i>
					  <br>

					  <div align="center">
						  <a data-toggle="collapse" href="#collapseExample{{$index}}" aria-expanded="false" aria-controls="collapseExample">
							  more
						  </a>
					  </div>
					  <div class="collapse" id="collapseExample{{$index}}" >
						  <div class="well">
							  {{paper.abstract}}
						  </div>
						  <div>
							  {{excerpt}}
						  </div>
					  </div>


					  <strong>
						  <span class="lib-link-full"><a id="link-74" class="track_this_link" style="display: none;" href="#" target="_blank">Full Text Available</a>
						  </span>
					  </strong>
				  </div>
				  <!--more-->
				  <hr class="lib-link-sep" />
			  </li>
		  </ul>
		  <div align="center">
			  <uib-pagination  boundary-links="true" total-items="totalItems" ng-model="currentPage" force-ellipses="true" max-size="maxSize" ng-change="pageChanged()" class="pagination-sm" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;"></uib-pagination>
		  </div>
	  </div>
  </div>
  </body>
</html>
