researchLibrary.controller('mainCtrl', function ($scope, $http, $location, $timeout, $q, $log, db) {

    init();

    function init(){
        $scope.sidebar_hide = true;
        $scope.aclist = [];
        $scope.currentPage = 1;
        $scope.searchitem = '';
        $scope.totalItems = parseInt(localStorage.getItem('totalItems'));
        $scope.len = 10;
        $scope.sugestlen = 10;
        $scope.maxSize = 5;
        $scope.papers = JSON.parse(localStorage.getItem('papers'));
        $scope.setPubTime = [];
        $scope.dataPubTime = [
            {id: 1, label: "David"}, {id: 2, label: "Jhon"}, {id: 3, label: "Danny"}
        ];
        $scope.setCat = [];
        $scope.dataCat = [
            {id: 1, label: "David"}, {id: 2, label: "Jhon"}, {id: 3, label: "Danny"}
        ];
        $scope.setKey = [];
        $scope.dataKey = [
            {id: 1, label: "David"}, {id: 2, label: "Jhon"}, {id: 3, label: "Danny"}
        ];
        $scope.setPubType = [];
        $scope.dataPubType = [
            {id: 1, label: "David"}, {id: 2, label: "Jhon"}, {id: 3, label: "Danny"}
        ];
        $scope.fBtnPubTime = {
            buttonDefaultText: "Publication Time",
            selectionCount:"Publication Time",
            dynamicButtonTextSuffix:"Publication Time"
        };
        $scope.fBtnCat = {
            buttonDefaultText: "Categories",
            selectionCount:"Categories",
            dynamicButtonTextSuffix:"Categories"
        };
        $scope.fBtnKey = {
            buttonDefaultText: "Keywords",
            selectionCount:"Keywords",
            dynamicButtonTextSuffix:"Keywords"
        };
        $scope.fBtnPubType = {
            buttonDefaultText: "Publication Type",
            selectionCount:"Publication Type",
            dynamicButtonTextSuffix:"Publication Type"
        };
        $scope.fSettingPubTime = {
                showCheckAll : false, showUncheckAll : false,
                imageURL: '/wordpress/wp-content/themes/genesis-sample/src/icon/clock.svg'
        };
        $scope.fSettingCat = {
            showCheckAll : false, showUncheckAll : false,
            imageURL: '/wordpress/wp-content/themes/genesis-sample/src/icon/category.svg'
        };
        $scope.fSettingKey = {
            showCheckAll : false, showUncheckAll : false,
            imageURL: '/wordpress/wp-content/themes/genesis-sample/src/icon/search_w_key.svg'
        };
        $scope.fSettingPubType = {
            showCheckAll : false, showUncheckAll : false,
            imageURL: '/wordpress/wp-content/themes/genesis-sample/src/icon/copy.svg'
        }

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
            localStorage.setItem('totalItems', $scope.totalItems);
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
        if (searchitem) {
            db.getPapersSearch(searchitem, 1, $scope.len).then(function (response){
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                if (response.data.results) {
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                }
            });
        } else {
            db.getPapersList($scope.currentPage, $scope.len).then(function (response){
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                if (response.data.results) {
                    localStorage.setItem('papers',  JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                }
            });
        }
    };
    $scope.onSelect = function ($item, $model, $label) {
        db.getPapersSearch($item.value, 1, $scope.len).then(function (response){
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);
            if (response.data.results) {
                localStorage.setItem('papers',  JSON.stringify($scope.papers));
                localStorage.setItem('totalItems', $scope.totalItems);
            }
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
