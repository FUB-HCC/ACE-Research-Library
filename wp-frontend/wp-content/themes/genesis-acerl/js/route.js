/**
 * Created by tarix on 03.07.16.
 */
researchLibrary.config(['$routeProvider', function($routeProvider) {

    $routeProvider.
    when('/search', {templateUrl: '../../wp-content/themes/genesis-acerl/search.html'}).
    when('/searchfull', {templateUrl: '../../wp-content/themes/genesis-acerl/searchfull.html'}).

    otherwise({redirectTo: '/search'});

}]);