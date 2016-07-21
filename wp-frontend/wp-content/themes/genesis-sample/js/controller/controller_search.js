researchLibrary.controller('searchCtrl', function ($scope, $http, $location, $timeout, $q, $log, db) {

    init();

    function init(){
        $scope.sidebar_hide = false;
        $scope.aclist = [];
        $scope.currentPage = 1;
        $scope.searchitem = '';
        $scope.len = 10;
        $scope.sugestlen = 10;
        $scope.papers = [];
    };

    function getPapers(){
        db.getPapersList($scope.currentPage, $scope.len).then(function (response){
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);
        });
    };

    $scope.getPapersSuggest = function(q) {
        db.getPapersSuggest(q, $scope.sugestlen).then(function (response){
            $scope.suggestlist = response.data.results;
        });
        return $scope.suggestlist;
    };

    function getfiletype(){
        for (i=0; i<$scope.papers.length; i++){
            if ($scope.papers[i].url.slice(-3) == "pdf"){
                $scope.papers[i].filetype = "pdf";
            }
            else $scope.papers[i].filetype = "html";
        }
    };

    $scope.search = function (searchitem) {
        if (searchitem) {
            db.getPapersSearch(searchitem, 1, $scope.len).then(function (response){
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                    $location.path('/searchfull');
                }
            });
        } else {
            db.getPapersList($scope.currentPage, $scope.len).then(function (response){
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers',  JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                    $location.path('/searchfull');
                }
            });
        }
    };
    $scope.onSelect = function ($item, $model, $label) {
        db.getPapersSearch($item.value, 1, $scope.len).then(function (response){
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);
            if (response.data.results) {
                getfiletype();
                localStorage.setItem('papers',  JSON.stringify($scope.papers));
                localStorage.setItem('totalItems', $scope.totalItems);
                $location.path('/searchfull');
            }
        });
    };
});
