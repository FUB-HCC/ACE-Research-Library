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

    function filter(PubTime, Cat, Key, PubType) {
        this.PubTime = PubTime;
        this.Cat = Cat;
        this.Key = Key;
        this.PubType = PubType;
        this.minyear = 1800;
        this.maxyear = 2016;
    }

    function getfiletype(){
        for (i=0; i<$scope.papers.length; i++){
            if ($scope.papers[i].url.slice(-3) == "pdf"){
                $scope.papers[i].filetype = "pdf";
            }
            else $scope.papers[i].filetype = "html";
        }
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

    function clearSetFilter() {
        $scope.setFilter = new filter([], [], [], []);
        localStorage.setItem('setFilter', JSON.stringify($scope.setFilter));
    };

    function newfilter(dataPubTime, dataCat, dataKey, dataPubType) {
        $scope.filter  = new filter(makearray(dataPubTime), makearray(dataCat),
            makearray(dataKey), makearray(dataPubType));
        $scope.filter.minyear = dataPubTime[0];
        clearSetFilter();
        localStorage.setItem('filter', JSON.stringify($scope.filter));
    };

    $scope.getPapersSuggest = function(q) {
        db.getPapersSuggest(q, $scope.sugestlen).then(function (response){
            $scope.suggestlist = response.data.results;
        });
        return $scope.suggestlist;
    };

    $scope.search = function (searchitem) {
        $scope.searchitem = searchitem;
        localStorage.setItem('searchitem', searchitem);
            db.getPapersSearch(searchitem, 1, $scope.len).then(function (response){
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                newfilter(response.data.published_list,
                    response.data.categories_list,
                    response.data.keywords_list,
                    response.data.resource_type_list
                );
                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                    $location.path('/searchfull');
                }
            });
    };
    $scope.onSelect = function ($item, $model, $label) {
        $scope.searchitem = $item.value;
        $scope.search($scope.searchitem);
    };
});
