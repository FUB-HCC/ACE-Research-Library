<?php /* Template Name: Example */ ?>
<html ng-app="phonecatApp">
  <head>
    
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/controllers.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap/collapse.js"></script>
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/bootstrap.css">
    <?php
     get_header();
    ?>
  </head>

  <body ng-controller="PhoneListCtrl">
    <div id="main">
      <div class="container main-container">

        <div class="row">
	  <div class="col-md-12">

	   <form name="Search">
		  Search: <input name="searchtext" type="text" ng-model="query" style="width: 1000px;height: 45;">
	   </form>

	   <div align="right">
		 <a data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
		  Filter
		 </a>
	   </div>

	   <div class="collapse" id="collapseExample">
		 <div class="well">
		  <div class="row">
		    <div class="col-md-1">
		      Year: 
		      <br>
		      Author:
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
	  </div>
        </div>

	<label>{{test}}</label>
<ul>
        <li ng-repeat="paper in papers | filter:query">
        	<i>
		<h4 class="lib-link-name"><a id="link-74" class="track_this_link " ng-title="{{paper.link.title}}" ng-href="{{paper.link.url}}" target="_blank" rel="nofollow">{{paper.link.text}}</a></h4></i>
		<div>
			<i> <small><span class="lib-link-citation">{{paper.meta}}</span></small>
			</i>
			<br>
			<span class="lib-link-desc">{{paper.comment}}</span>

			<strong>
				<span class="lib-link-full"><a id="link-74" class="track_this_link" style="display: none;" href="#" target="_blank">Full Text Available</a>
				</span>
			</strong>
		</div>
	<!--more-->
	<hr class="lib-link-sep" />
	</li>
</ul>


</div>
</div>
</body>
</html>
