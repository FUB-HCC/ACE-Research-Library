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
        });
    };
    $scope.pageChanged = function() {
        getPapers();
    };

    $scope.getPapersSuggest = function(q) {
        db.getPapersSuggest(q, $scope.sugestlen).then(function (response){
            $scope.suggestlist = response.data.results;
        });
        return $scope.suggestlist;
    };

    $scope.search = function (searchitem) {
        db.getPapersSearch(searchitem, 1, $scope.len).then(function (response){
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);

            $scope.filter_frame = prototype_filter_frame();
            if (response.data) { $scope.sidebar_hide = true;}
            build_filter();
        });
    };
    $scope.onSelect = function ($item, $model, $label) {
        db.getPapersSearch($item.value, 1, $scope.len).then(function (response){
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);
            if (response.data) { $scope.sidebar_hide = true;}
            $scope.filter_frame = prototype_filter_frame();
            build_filter();
        });
    };
    $scope.onFilter = function(index){
        var cat = $scope.filter_frame.categories[index];
        if (!$scope.origpapers) $scope.origpapers = $scope.papers;
        $scope.papers = $scope.origpapers.filter(function (item, pos) {return item.categories.indexOf(cat) >=0 });
    };

    function build_filter(){
        for (i=0; i< $scope.papers.length; i++) {
            if ($scope.papers[i].categories) {
                $scope.filter_frame.categories = $scope.filter_frame.categories.concat($scope.papers[i].categories);
            }
            if (($scope.filter_frame.publication_type.indexOf($scope.papers[i].resource_type))<0) {
                $scope.filter_frame.publication_type.push($scope.papers[i].resource_type);
            };
            if (($scope.filter_frame.years.indexOf($scope.papers[i].published))<0) {
                $scope.filter_frame.years.push($scope.papers[i].published);
            };
        };
        var cat = $scope.filter_frame.categories;
        var res = cat.filter(function (item, pos) {return cat.indexOf(item) == pos});
        $scope.filter_frame.categories = res;
        $scope.filter_frame.categories.sort();
        $scope.filter_frame.publication_type.sort();
        $scope.filter_frame.years.sort();
    };

});
