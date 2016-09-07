/**
 * Created by tarix on 16.05.16.
 */
researchLibrary.factory('db', ['$rootScope', '$http', 'config', '$q', function($rootScope, $http, config,  $q) {

    return {
        getPapersList: function (page, len) {
            var queryUrl = config.url + 'list/?' + 'page=' + page+'&len='+len;
            return $http.get(queryUrl);
        },
        getPapersSearch: function (q, page, len, strfilter) {
            if (strfilter) { var queryUrl = config.url + 'search/?' + 'page=' + page+'&len='+len + '&q=' + q + strfilter}
                else { var queryUrl = config.url + 'search/?' + 'page=' + page+'&len='+len + '&q=' + q; }
            return $http.get(queryUrl);
        },
        getPapersSearchOrder: function (q, page, len, order, strfilter) {
            if (strfilter) { var queryUrl = config.url + 'search/?' + 'page=' + page+'&len='+len + '&q=' + q + '&sort=' + order + strfilter;}
                else { var queryUrl = config.url + 'search/?' + 'page=' + page+'&len='+len + '&q=' + q + '&sort=' + order;}
            return $http.get(queryUrl);
        },
        getPapersSuggest: function (q, len) {
            var queryUrl = config.url + 'suggest/?' +'len='+len + '&q=' + q;
            return $http.get(queryUrl);
        },
    };
    }]);
