Ticketisy.controller('ServicesController', function($scope, $http) {
    $http.get('/api/service', {
        params: {
            api_token: $scope.api_token
        }
    }).success(function(response) {
        $scope.services = response.data;
        $scope.empty = !response.data.length;
    }).error(function(response) {
        console.log('error');
    });
});
