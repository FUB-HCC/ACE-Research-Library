researchLibrary.controller('mainCtrl', function ($scope, $http, $timeout, $q, $log, db) {

    $scope.len = 10;
    $scope.sugestlen = 10;
    db.getPapersList($scope.len).then(function(response) {
        //$scope.papers = response.data.result;
        $scope.papers = response.data;
        build_filter();
    });
    init();

    function init(){
        $scope.sidebar_hide = false;
        $scope.aclist = [];
        $scope.filter_frame = prototype_filter_frame();
        $scope.currentPage = 0;
        $scope.totalItems = 64;
        $scope.searchitem = '';
    };
    function prototype_filter_frame() {
        filter_frame = {
            years: [],
            categories: [],
            publication_type: []
        };
        return filter_frame;
    };

    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };

    $scope.pageChanged = function() {
        $log.log('Page changed to: ' + $scope.currentPage);
        db.getPapersSearch($scope.searchitem, $scope.currentPage, $scope.len).then(function (response){
            //$scope.papers = response.data.result;
            $scope.papers = response.data;
        });

    };

    $scope.getPapersSuggest = function(q) {
        db.getPapersSuggest(q, $scope.sugestlen).then(function (response){
            $scope.suggestlist = response.data;
        });
        return $scope.suggestlist;
    };

    $scope.search = function (searchitem) {
        console.log(searchitem);
        db.getPapersSearch(searchitem, 1, $scope.len).then(function (response){
            $scope.papers = response.data;
            $scope.filter_frame = prototype_filter_frame();
            if (response.data) { $scope.sidebar_hide = true;}
            build_filter();
            console.log(filter_frame);
        });
    };

    function build_filter(){
        for (i=0; i< $scope.papers.length; i++) {
            console.log($scope.filter_frame.categories.indexOf($scope.papers[i].category));
            if (($scope.filter_frame.categories.indexOf($scope.papers[i].category))<0) {
                $scope.filter_frame.categories.push($scope.papers[i].category);
            };
            if (($scope.filter_frame.publication_type.indexOf($scope.papers[i].sourcetype))<0) {
                $scope.filter_frame.publication_type.push($scope.papers[i].sourcetype);
            };
            if (($scope.filter_frame.years.indexOf($scope.papers[i].date))<0) {
                $scope.filter_frame.years.push($scope.papers[i].date);
            };
        };
        $scope.filter_frame.categories.sort();
        $scope.filter_frame.publication_type.sort();
        $scope.filter_frame.years.sort();
    };

});