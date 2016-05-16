researchLibrary.controller('mainCtrl', function ($scope, $http, $timeout, $q, $log, db) {

    db.getAutoPapers().then(function(response) {
        $scope.papers = response.data;
        build_filter();
    });
    init();

    function init(){
        $scope.sidebar_hide = false;
        $scope.aclist = [];
        $scope.filter_frame = prototype_filter_frame();
    };
    function prototype_filter_frame() {
        filter_frame = {
            years: [],
            categories: [],
            publication_type: []
        };
        return filter_frame;

    }
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

    $scope.getAutoPaper = function(val) {
        db.getAutoPapers(val).then(function (response){
            $scope.aclist = response.data;
        });
        return $scope.aclist;
    };

    $scope.search = function (searchitem) {
        console.log(searchitem);
        $scope.filter_frame = prototype_filter_frame();
        if (searchitem) { $scope.sidebar_hide = true;}

        build_filter()
        console.log(filter_frame);
    };

});