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
        $scope.descending = false;
        $scope.slider = {
            min: 1800,
            max: 2016,
            step: 1,
            show: false,
            options: {
                floor: 1800,
                ceil: 2016
            }
        };
        if (($scope.setFilter.minyear) || ($scope.setFilter.maxyear)) {
            $scope.slider.min = $scope.setFilter.minyear;
            $scope.slider.max = $scope.setFilter.maxyear;
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
                showCheckAll : false, showUncheckAll : false, closeOnSelect : false,
                imageURL: '../wp-content/themes/genesis-acerl/img/icon/clock.svg'
        };
        $scope.fSettingCat = {
            showCheckAll : false, showUncheckAll : false, closeOnSelect : false,
            imageURL: '../wp-content/themes/genesis-acerl/img/icon/category.svg'
        };
        $scope.fSettingKey = {
            showCheckAll : false, showUncheckAll : false, closeOnSelect : false,
            imageURL: '../wp-content/themes/genesis-acerl/img/icon/search_w_key.svg'
        };
        $scope.fSettingPubType = {
            showCheckAll : false, showUncheckAll : false, closeOnSelect : false,
            imageURL: '../wp-content/themes/genesis-acerl/img/icon/copy.svg'
        };

        $scope.eventsPubTime = {
            onItemSelect : function (item) { filterSelect(item, 'PubTime', false)},
            onItemDeselect : function (item) { filterSelect(item, 'PubTime', true)},
            onDeselectAll : function () { filterSelect(item, 'PubTime', true)}
        };
        $scope.eventsCat = {
            onItemSelect : function (item) { filterSelect(item, 'Cat', false)},
            onItemDeselect : function (item) { filterSelect(item, 'Cat', true)},
            onDeselectAll : function () { filterSelect(0, 'Cat', true); return true}
        };
        $scope.eventsKey = {
            onItemSelect : function (item) { filterSelect(item, 'Key', false)},
            onItemDeselect : function (item) { filterSelect(item, 'Key', true)},
            onDeselectAll : function () { filterSelect(0, 'Key', true); return true}
        };
        $scope.eventsPubType = {
            onItemSelect : function (item) { filterSelect(item, 'PubType', false)},
            onItemDeselect : function (item) { filterSelect(item, 'PubType', true)},
            onDeselectAll : function () { filterSelect(0, 'PubType', true); return true}
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
        $scope.setFilter.minyear = $scope.slider.min;
        $scope.setFilter.maxyear = $scope.slider.max;
        filterSelect($scope.slider, "yearrange")
    });

    function filter(PubTime, Cat, Key, PubType) {
        this.PubTime = PubTime;
        this.Cat = Cat;
        this.Key = Key;
        this.PubType = PubType;
        this.minyear = 1800;
        this.maxyear = 2016;
    }

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
        $scope.filter = new filter([], [], [], []);
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
        localStorage.setItem('filter', JSON.stringify($scope.filter));
    };

    function renewFilterPubTime(data){
        $scope.filter.PubTime = makearray(data);
    };
    function renewFilterCat(data){
        $scope.filter.Cat = makearray(data);
    };
    function renewFilterKey(data){
        $scope.filter.Key = makearray(data);
    };
    function renewFilterPubType(data){
        $scope.filter.PubType = makearray(data);
    };
    function modifyFilterPubTime(data) {
        var newarr = makearray(data);
        $scope.setFilter.PubTime = newarr;
        $scope.filter.PubTime = angular.copy(newarr);
    }
    function modifyFilterCat(data) {
        var newarr = makearray(data);
        $scope.setFilter.Cat = newarr;
        $scope.filter.Cat = angular.copy(newarr);
    }
    function modifyFilterPubType(data) {
        var newarr = makearray(data);
        $scope.setFilter.PubType = newarr;
        $scope.filter.PubType = angular.copy(newarr);
    }
    function modifyFilterKey(data) {
        //var newarr = $scope.setFilter.Key;
        var simplearr = [];
        var newarr = [];
        //SetFilter wieder nummerieren
        for (i = 0; i<$scope.setFilter.Key.length; i++) {
            var item = {
                id: i,
                label: $scope.filter.Key[$scope.setFilter.Key[i].id].label
            }
            newarr[i] = item;
            simplearr.push(item.label);
        }
        $scope.setFilter.Key = angular.copy(newarr);

        //formated array to array
        j = i + 1;                              // it is ($scope.setFilter.length)
        for (t = 0; j<10, t<data.length; t++) {
            if (!(simplearr.indexOf(data[t])>=0)){
                var item = {
                    id: j,
                    label: data[t]
                };
                newarr[j] = item;
                j++;
            }
        }

        $scope.filter.Key = newarr;
    }

    function changefilter(dataPubTime, dataCat, dataKey, dataPubType, str, des) {

        if (!(str == 'PubTime') || des){
            if ($scope.setFilter.PubTime.length == 0) {
                renewFilterPubTime(dataPubTime)
            } else {
                modifyFilterPubTime(dataPubTime)
            }
        };

        if (!(str == 'Cat') || des) {
            if ($scope.setFilter.Cat.length == 0) {
                renewFilterCat(dataCat);// only filter
            } else {
                modifyFilterCat(dataCat); // filter and setfilter
            }
        };

        if (!(str == 'PubType') || des) {
            if ($scope.setFilter.PubType.length == 0) {
                renewFilterPubType(dataPubType)
            } else {
                modifyFilterPubType(dataPubType)
            }
        }

        if ($scope.setFilter.Key.length == 0) {
            renewFilterKey(dataKey)
        } else {
            modifyFilterKey(dataKey)
        }
        localStorage.setItem('filter', JSON.stringify($scope.filter));
        localStorage.setItem('setFilter', JSON.stringify($scope.setFilter));
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
        strFilter += '&minyear=' + $scope.setFilter.minyear + '&maxyear=' + $scope.setFilter.maxyear;
        return strFilter;
    }

    function filterSelect(item, str, des){
        $scope.strFilter = setFiltertoStr();
        db.getPapersSearch($scope.searchitem, 1, $scope.len, $scope.strFilter).then(function (response) {
            $scope.totalItems = response.data.count;
            angular.copy(response.data.results, $scope.papers);
            changefilter(response.data.published_list,
                    response.data.categories_list,
                    response.data.keywords_list,
                    response.data.resource_type_list, str, des);
            if (response.data.results) {
                getfiletype();
                localStorage.setItem('papers', JSON.stringify($scope.papers));
                localStorage.setItem('totalItems', $scope.totalItems);
                localStorage.setItem('strFilter', $scope.strFilter);
            };
        });
    };

    $scope.sort = function(sortby){
        if ((sortby==='date') && ($scope.descending)) { sortby = '-date' };
        $scope.descending = !$scope.descending;
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
    };

    $scope.onSelect = function ($item, $model, $label) {
        $scope.searchitem = $item.value;
        $scope.search($scope.searchitem);
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
    };

    $scope.showSlider = function() {
        if ($scope.slider.show) $scope.slider.show = false;
        else $scope.slider.show = true;
    }

});

researchLibrary.filter('safe', function($sce) { return $sce.trustAsHtml; });
