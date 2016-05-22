Ticketisy.controller('UsersController', function($scope, $http) {
    $http.get('/api/user', {
        params: {
            api_token: $scope.api_token,
            role: 'user',
        }
    }).success(function(response) {
        $scope.users = response.data;
    });
});
