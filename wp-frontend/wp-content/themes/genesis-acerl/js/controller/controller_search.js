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

    function clearfilter() {
        $scope.papers=null;
        $scope.dataPubTime=null;
        $scope.dataCat=null;
        $scope.dataKey=null;
        $scope.dataPubType=null;
    };

    function makearray(arr){
        var array = [];
        for(i=0; i < arr.length; i++){
            var item = {
                id: i,
                label: arr[i]
            }
            array[i] = item;
        }
        return array;
    };

    function fillfilter(dataPubTime, dataCat, dataKey, dataPubType) {
        $scope.dataPubTime  = makearray(dataPubTime);
        $scope.dataCat = makearray(dataCat);
        $scope.dataKey = makearray(dataKey);
        $scope.dataPubType = makearray(dataPubType);
    };

    $scope.search = function (searchitem) {
        $scope.searchitem = searchitem;
        localStorage.setItem('searchitem', searchitem);
        if (searchitem) {
            db.getPapersSearch(searchitem, 1, $scope.len).then(function (response){
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                fillfilter(response.data.published_list,
                    response.data.categories_list,
                    response.data.keywords_list,
                    response.data.resource_type_list
                );
                var filter = {
                    dataPubTime: $scope.dataPubTime,
                    dataCat: $scope.dataCat,
                    dataKey: $scope.dataKey,
                    dataPubType: $scope.dataPubType
                };
                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                    localStorage.setItem('filter', JSON.stringify(filter));
                    $location.path('/searchfull');
                }
            });
        } else {
            db.getPapersList($scope.currentPage, $scope.len).then(function (response){
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                clearfilter();
                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers',  JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                    localStorage.setItem('filter', '');
                    $location.path('/searchfull');
                }
            });
        }
    };
    $scope.onSelect = function ($item, $model, $label) {
        $scope.searchitem = $item.value;
        localStorage.setItem('searchitem', $item.value);

        db.getPapersSearch($item.value, 1, $scope.len).then(function (response){
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);
            fillfilter(response.data.published_list,
                response.data.categories_list,
                response.data.keywords_list,
                response.data.resource_type_list
            );
            var filter = {
                dataPubTime: $scope.dataPubTime,
                dataCat: $scope.dataCat,
                dataKey: $scope.dataKey,
                dataPubType: $scope.dataPubType
            };
            if (response.data.results) {
                getfiletype();
                localStorage.setItem('papers',  JSON.stringify($scope.papers));
                localStorage.setItem('totalItems', $scope.totalItems);
                localStorage.setItem('filter', JSON.stringify(filter));
                $location.path('/searchfull');
            }
        });
    };
});
