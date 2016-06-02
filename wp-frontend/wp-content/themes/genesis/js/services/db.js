/**
 * Created by tarix on 16.05.16.
 */
researchLibrary.factory('db', ['$rootScope', '$http', 'config', '$q', function($rootScope, $http, config,  $q) {

    return {
        getPapersList: function (page, len) {
            var queryUrl = config.url + 'paper.json';
            var queryUrl_cop = config.url + 'list/?' + 'page=' + page+'&len='+len;
            return $http.get(queryUrl_cop);
        },
        getPapersSearch: function (q, page, len) {
            var queryUrl = config.url + 'paper.json';
            var queryUrl_cop = config.url + 'search/?' + 'page=' + page+'&len='+len + '&q=' + q;
            return $http.get(queryUrl_cop);
        },
        getPapersSuggest: function (q, len) {
            var queryUrl = config.url + 'paper.json';
            /* you must write parameter for queries here down*/
            var queryUrl_cop = config.url + '?' +'len='+len + '&q=' + q;
            return $http.get(queryUrl);
        },
    };
    }]);
