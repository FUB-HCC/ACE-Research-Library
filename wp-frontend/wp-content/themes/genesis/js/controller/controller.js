researchLibrary.controller('mainCtrl', function ($scope, $http, $timeout, $q, $log, db) {

    //$scope.maxSize = 7;
    init();
    getPapers();

    function init(){
        $scope.sidebar_hide = false;
        $scope.aclist = [];
        $scope.currentPage = 1;
        $scope.searchitem = '';
        $scope.len = 10;
        $scope.sugestlen = 10;
        $scope.papers = [];
        $scope.maxSize = 5;
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

    function getPapers(){
        db.getPapersList($scope.currentPage, $scope.len).then(function (response){
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);
            console.log($scope.papers);
            console.log($scope.totalItems);

        });
    };
    $scope.pageChanged = function() {
        $log.log('Page changed to: ' + $scope.currentPage);
        getPapers();
    };

    $scope.getPapersSuggest = function(q) {
        db.getPapersSuggest(q, $scope.sugestlen).then(function (response){
            $scope.suggestlist = response.data.results;
        });
        return $scope.suggestlist;
    };

    $scope.search = function (searchitem) {
        console.log(searchitem);
        db.getPapersSearch(searchitem, 1, $scope.len).then(function (response){
            $scope.papers = response.data.results;
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
