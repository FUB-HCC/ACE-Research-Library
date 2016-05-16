/**
 * Created by tarix on 16.05.16.
 */
researchLibrary.factory('db', ['$rootScope', '$http', 'config', '$q', function($rootScope, $http, config,  $q) {

    return {
        getAutoPapers: function (val) {
            var queryUrl = config.url + 'paper.json';
            /* you must write parameter for queries here down*/
            return $http.get(queryUrl);

        }
    };
    }]);