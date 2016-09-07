researchLibrary.controller('mainCtrl', function ($scope, $http, $location, $timeout, $q, $log, db) {

    init();

    function init(){
        $scope.sidebar_hide = true;
        $scope.aclist = [];
        $scope.currentPage = 1;
        $scope.searchitem = '';
        $scope.searchitem = localStorage.getItem('searchitem');
        $scope.totalItems = parseInt(localStorage.getItem('totalItems'));
        $scope.len = 10;
        $scope.sugestlen = 10;
        $scope.maxSize = 5;
        $scope.papers = JSON.parse(localStorage.getItem('papers'));
        $scope.filter = JSON.parse(localStorage.getItem('filter'));
        $scope.sortby = 'date';
        $scope.setPubTime = [];
        $scope.setCat = [];
        $scope.setKey = [];
        $scope.setPubType = [];
        $scope.dataPubTime = $scope.filter.dataPubTime;
        $scope.dataCat = $scope.filter.dataCat;
        $scope.dataKey = $scope.filter.dataKey;
        $scope.dataPubType = $scope.filter.dataPubType;
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
                imageURL: '../wp-content/themes/genesis-acerl/src/icon/clock.svg'
        };
        $scope.fSettingCat = {
            showCheckAll : false, showUncheckAll : false, closeOnSelect : true,
            imageURL: '../wp-content/themes/genesis-acerl/src/icon/category.svg'
        };
        $scope.fSettingKey = {
            showCheckAll : false, showUncheckAll : false, closeOnSelect : true,
            imageURL: '../wp-content/themes/genesis-acerl/src/icon/search_w_key.svg'
        };
        $scope.fSettingPubType = {
            showCheckAll : false, showUncheckAll : false, closeOnSelect : true,
            imageURL: '../wp-content/themes/genesis-acerl/src/icon/copy.svg'
        };

        if ($scope.filter='') $scope.filter=null;

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
        getList(true);
    };

    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };

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
        }
        else {
            db.getPapersSearchOrder($scope.searchitem, $scope.currentPage, $scope.len, $scope.sortby, $scope.strfilter).then(function (response) {
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
        //delete selectitems for filter
        $scope.setPubTime = [];
        $scope.setCat = [];
        $scope.setKey = [];
        $scope.setPubType = [];
        $scope.dataPubTimeB  = $scope.dataPubTime;
        $scope.dataCatB = $scope.dataCat;
        $scope.dataKeyB = $scope.dataKey;
        $scope.dataPubTypeB = $scope.dataPubType;

    };

    function changefilter(dataPubTime, dataCat, dataKey, dataPubType) {

        $scope.dataPubTime = $scope.dataPubTimeB.filter(function(item){
            for (i=0; i<dataPubTime.length; i++){
                if (dataPubTime[i] == item.label){
                    return true;
                }
            }
            return false;
        });
        $scope.dataCat = $scope.dataCatB.filter(function(item){
            for (i=0; i<dataCat.length; i++){
                if (dataCat[i] == item.label){
                    return true;
                }
            }
            return false;
        });
        $scope.dataKey = $scope.dataKeyB.filter(function(item){
            for (i=0; i<dataKey.length; i++){
                if (dataKey[i] == item.label){
                    return true;
                }
            }
            return false;
        });
        $scope.dataPubType = $scope.dataPubTypeB.filter(function(item){
            for (i=0; i<dataPubType.length; i++){
                if (dataPubType[i] == item.label){
                    return true;
                }
            }
            return false;
        });
    };

    function filterSelect(item, str){
        var strfilter = '';
        if (str == 'PubTime'){
            for(i=0; i<$scope.setPubTime.length; i++){
                strfilter += '&pubfilter=' + $scope.dataPubTime[$scope.setPubTime[i].id].label;
            }
            console.log(strfilter);

            db.getPapersSearch($scope.searchitem, 1, $scope.len, strfilter).then(function (response) {
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                changefilter(response.data.published_list,
                    response.data.categories_list,
                    response.data.keywords_list,
                    response.data.resource_type_list
                );

                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);

                };
            });
        };
        if (str == 'Cat'){
            for(i=0; i<$scope.setCat.length; i++){
                strfilter += '&catfilter=' + $scope.dataCat[$scope.setCat[i].id].label;
            }
            console.log(strfilter);

            db.getPapersSearch($scope.searchitem, 1, $scope.len, strfilter).then(function (response) {
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                changefilter(response.data.published_list,
                    response.data.categories_list,
                    response.data.keywords_list,
                    response.data.resource_type_list
                );

                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                };
            });
        };
        if (str == 'Key'){
            for(i=0; i<$scope.setKey.length; i++){
                strfilter += '&kywfilter=' + $scope.dataKey[$scope.setKey[i].id].label;
            }
            console.log(strfilter);

            db.getPapersSearch($scope.searchitem, 1, $scope.len, strfilter).then(function (response) {
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                changefilter(response.data.published_list,
                    response.data.categories_list,
                    response.data.keywords_list,
                    response.data.resource_type_list
                );

                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                };
            });
        };
        if (str == 'PubType'){
            for(i=0; i<$scope.setPubType.length; i++){
                strfilter += '&rstfilter=' + $scope.dataPubType[$scope.setPubType[i].id].label;
            }
            console.log(strfilter);

            db.getPapersSearch($scope.searchitem, 1, $scope.len, strfilter).then(function (response) {
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                changefilter(response.data.published_list,
                    response.data.categories_list,
                    response.data.keywords_list,
                    response.data.resource_type_list
                );

                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                };
            });
        };
        $scope.strfilter = strfilter;
    };



    $scope.sort = function(sortby){
        db.getPapersSearchOrder($scope.searchitem, 1, $scope.len, sortby, $scope.strfilter).then(function (response) {
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
        localStorage.setItem('searchitem', searchitem);
        if (searchitem) {
            db.getPapersSearch(searchitem, 1, $scope.len).then(function (response) {
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
            db.getPapersList($scope.currentPage, $scope.len).then(function (response) {
                $scope.totalItems = response.data.count;
                angular.copy(response.data.results, $scope.papers);
                clearfilter();
                if (response.data.results) {
                    getfiletype();
                    localStorage.setItem('papers', JSON.stringify($scope.papers));
                    localStorage.setItem('totalItems', $scope.totalItems);
                    localStorage.setItem('filter', '');
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
