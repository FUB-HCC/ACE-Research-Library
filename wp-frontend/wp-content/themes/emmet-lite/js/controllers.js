var phonecatApp = angular.module('phonecatApp', []);

phonecatApp.controller('PhoneListCtrl', function ($scope, $http) {

  $http.get('http://192.168.151.128/src/paper.json').success(function(data) {
    $scope.papers = data;
  });

});
