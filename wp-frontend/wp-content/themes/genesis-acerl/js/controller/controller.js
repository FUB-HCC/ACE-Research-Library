researchLibrary.controller('mainCtrl', function ($scope, $http, $location, $timeout, $q, $log, db) {

    init();

    function init(){
        $scope.sidebar_hide = true;
        $scope.aclist = [];
        //pagination
        $scope.currentPage = 1;
        $scope.searchitem = localStorage.getItem('searchitem');
        $scope.totalItems = parseInt(localStorage.getItem('totalItems'));
        $scope.len = 10;
        $scope.sugestlen = 10;
        $scope.maxSize = 5;
        $scope.papers = JSON.parse(localStorage.getItem('papers'));
        $scope.filter = JSON.parse(localStorage.getItem('filter'));
        $scope.setFilter = JSON.parse(localStorage.getItem('setFilter'));
        console.log($scope.setFilter);
        //wenn LocalcStorage leer ist, was dann?????

        $scope.sortby = 'relevance';
        $scope.slider = {
            min: 1001,
            max: 2015,
            options: {
                floor: 1000,
                ceil: 2016
            }
        };
        $scope.fBtnPubTime = {
            buttonDefaultText: 'Publication Time',
            selectionCount:'Publication Time',
            dynamicButtonTextSuffix:'Publication Time'
        };
        $scope.fBtnCat = {
            buttonDefaultText: 'Categories',
            selectionCount:'Categories',
            dynamicButtonTextSuffix:'Categories'
        };
        $scope.fBtnKey = {
            buttonDefaultText: 'Keywords',
            selectionCount:'Keywords',
            dynamicButtonTextSuffix:'Keywords'
        };
        $scope.fBtnPubType = {
            buttonDefaultText: 'Publication Type',
            selectionCount:'Publication Type',
            dynamicButtonTextSuffix:'Publication Type'
        };
        $scope.fSettingPubTime = {
                showCheckAll : false, showUncheckAll : false, closeOnSelect : true,
                imageURL: '../wp-content/themes/genesis-acerl/img/icon/clock.svg'
        };
        $scope.fSettingCat = {
            showCheckAll : false, showUncheckAll : false, closeOnSelect : true,
            imageURL: '../wp-content/themes/genesis-acerl/img/icon/category.svg'
        };
        $scope.fSettingKey = {
            showCheckAll : false, showUncheckAll : false, closeOnSelect : true,
            imageURL: '../wp-content/themes/genesis-acerl/img/icon/search_w_key.svg'
        };
        $scope.fSettingPubType = {
            showCheckAll : false, showUncheckAll : false, closeOnSelect : true,
            imageURL: '../wp-content/themes/genesis-acerl/img/icon/copy.svg'
        };

        $scope.eventsPubTime = {
            onItemSelect : function (item) { filterSelect(item, 'PubTime')},
            onItemDeselect : function (item) { filterSelect(item, 'PubTime')}
        };
        $scope.eventsCat = {
            onItemSelect : function (item) { filterSelect(item, 'Cat')},
            onItemDeselect : function (item) { filterSelect(item, 'Cat')}
        };
        $scope.eventsKey = {
            onItemSelect : function (item) { filterSelect(item, 'Key')},
            onItemDeselect : function (item) { filterSelect(item, 'Key')}
        };
        $scope.eventsPubType = {
            onItemSelect : function (item) { filterSelect(item, 'PubType')},
            onItemDeselect : function (item) { filterSelect(item, 'PubType')}
        };
        //getList(true);
    };

    //pagination -> change page
    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };

    //range-slider -> changed
    $scope.$on("slideEnded", function() {
        console.log($scope.slider);
    });

    function filter(PubTime, Cat, Key, PubType) {
        this.PubTime = PubTime;
        this.Cat = Cat;
        this.Key = Key;
        this.PubType = PubType;
    }

    function getPapers(){
        db.getPapersList($scope.currentPage, $scope.len).then(function (response){
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);
            if (response.data.results) {
                getfiletype();
                localStorage.setItem('papers', JSON.stringify($scope.papers));
                localStorage.setItem('totalItems', $scope.totalItems);
            };
        });
    };
    function getList(renew){
        if (renew) {
            db.getPapersSearchOrder($scope.searchitem, 1, $scope.len, $scope.sortby).then(function (response) {
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
                };
            });
        } else {
            db.getPapersSearchOrder($scope.searchitem, $scope.currentPage, $scope.len, $scope.sortby, $scope.strFilter).then(function (response) {
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                };
            });
        }
    };

    function getfiletype(){
        for (i=0; i<$scope.papers.length; i++) {
            if ($scope.papers[i].url.slice(-3) == 'pdf') {
                $scope.papers[i].filetype = 'pdf';
            } else {
                $scope.papers[i].filetype = 'html';
            }
            $scope.papers[i].full = false;
        }
    };

    function clearFilter() {
        $scope.filter = new filter([], [], [], []);;
        localStorage.setItem('filter', JSON.stringify($scope.filter));
    };

    function clearSetFilter() {
        $scope.setFilter = new filter([], [], [], []);
        localStorage.setItem('setFilter', JSON.stringify($scope.setFilter));
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

    function newfilter(dataPubTime, dataCat, dataKey, dataPubType) {
        $scope.filter  = new filter(makearray(dataPubTime), makearray(dataCat),
                        makearray(dataKey), makearray(dataPubType));
        //delete selectitems for filter
        clearSetFilter();
        $scope.dataPubTimeB  = $scope.filter.PubTime;
        $scope.dataCatB = $scope.filter.Cat;
        $scope.dataKeyB = $scope.filter.Key;
        $scope.dataPubTypeB = $scope.filter.PubType;
        localStorage.setItem('filter', JSON.stringify($scope.filter));
    };

    function changefilter(dataPubTime, dataCat, dataKey, dataPubType, str) {

        if (!(str == 'PubTime')){
            $scope.filter.PubTime = $scope.dataPubTimeB.filter(function(item){
                for (i=0; i<dataPubTime.length; i++){
                    if (dataPubTime[i] == item.label){
                        return true;
                    }
                }
                return false;
            });
        }
        if (!(str == 'Cat')) {
            $scope.filter.Cat = $scope.dataCatB.filter(function (item) {
                for (i = 0; i < dataCat.length; i++) {
                    if (dataCat[i] == item.label) {
                        return true;
                    }
                }
                return false;
            });
        }

        $scope.filter.dataKey = $scope.dataKeyB.filter(function (item) {
            for (i = 0; i < dataKey.length; i++) {
                if (dataKey[i] == item.label) {
                    return true;
                }
            }
            return false;
        });

        if (!(str == 'PubType')) {
            $scope.filter.PubType = $scope.dataPubTypeB.filter(function (item) {
                for (i = 0; i < dataPubType.length; i++) {
                    if (dataPubType[i] == item.label) {
                        return true;
                    }
                }
                return false;
            });
        }
        localStorage.setItem('filter', JSON.stringify($scope.filter));
    };

    function setFiltertoStr() {
        var strFilter = '';
        //checken setFilter, ich vermute, dass die SChleife uebrig ist
        for(i=0; i<$scope.setFilter.PubTime.length; i++){
            strFilter += '&pubfilter=' + $scope.filter.PubTime[$scope.setFilter.PubTime[i].id].label;
        };
        for(i=0; i<$scope.setFilter.Cat.length; i++){
            strFilter += '&catfilter=' + $scope.filter.Cat[$scope.setFilter.Cat[i].id].label;
        };
        for(i=0; i<$scope.setFilter.Key.length; i++){
            strFilter += '&kywfilter=' + $scope.filter.Key[$scope.setFilter.Key[i].id].label;
        };
        for(i=0; i<$scope.setFilter.PubType.length; i++){
            strFilter += '&rstfilter=' + $scope.filter.PubType[$scope.setFilter.PubType[i].id].label;
        };
        return strFilter;
    }

    function filterSelect(item, str){
        $scope.strFilter = setFiltertoStr();
        db.getPapersSearch($scope.searchitem, 1, $scope.len, $scope.strFilter).then(function (response) {
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);
            changefilter(response.data.published_list,
                response.data.categories_list,
                response.data.keywords_list,
                response.data.resource_type_list, str
            );

            if (response.data.results) {
                getfiletype();
                localStorage.setItem('papers', JSON.stringify($scope.papers));
                localStorage.setItem('totalItems', $scope.totalItems);
                localStorage.setItem('setPubTime', $scope.setPubTime);
                localStorage.setItem('strFilter', $scope.strFilter);
            };
        });
    };



    $scope.sort = function(sortby){
        db.getPapersSearchOrder($scope.searchitem, 1, $scope.len, sortby, $scope.strFilter).then(function (response) {
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);
            $scope.sortby = sortby;
            if (response.data.results) {
                getfiletype();
                localStorage.setItem('papers',  JSON.stringify($scope.papers));
                localStorage.setItem('totalItems', $scope.totalItems);
            };
        });
    };

    $scope.pageChanged = function() {
        getList();
    };

    $scope.getPapersSuggest = function(q) {
        db.getPapersSuggest(q, $scope.sugestlen).then(function (response){
            $scope.suggestlist = response.data.results;
        });
        return $scope.suggestlist;
    };

    $scope.search = function (searchitem) {
        $scope.searchitem = searchitem;
        if (searchitem) {
            db.getPapersSearch(searchitem, 1, $scope.len).then(function (response) {
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
                    localStorage.setItem('searchitem', searchitem);
                };
            });
        } else {
            db.getPapersList($scope.currentPage, $scope.len).then(function (response) {
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                clearFilter();
                clearSetFilter();
                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                };
            });
        }
    };
    $scope.onSelect = function ($item, $model, $label) {
        $scope.searchitem = $item.value;
        localStorage.setItem('filter', $item.value);
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
                localStorage.setItem('papers', JSON.stringify($scope.papers));
                localStorage.setItem('totalItems', $scope.totalItems);
                localStorage.setItem('filter', JSON.stringify(filter));
            }
        });
    };
    $scope.onFilter = function(index) {
        var cat = $scope.filter_frame.categories[index];
        if (!$scope.origpapers) $scope.origpapers = $scope.papers;
        $scope.papers = $scope.origpapers.filter(function(item, pos) {
            return item.categories.indexOf(cat) >= 0;
        });
    };

    $scope.onFullview = function(index) {
        if ($scope.papers[index].full) $scope.papers[index].full = false;
        else $scope.papers[index].full = true;
    }

});

researchLibrary.filter('safe', function($sce) { return $sce.trustAsHtml; });
