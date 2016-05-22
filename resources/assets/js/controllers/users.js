Ticketisy.controller('UsersController', function($scope, $http) {
    $http.get('/api/user', {
        params: {
            role: 'user',
        }
    }).success(function(response) {
        $scope.users = response.data;
    });
});

Ticketisy.controller('UserDetailsController', function($scope, $http, $stateParams) {
    var user_id = $stateParams.id;

    $http.get('/api/user/' + user_id).success(function(response) {
        $scope.user = response;
    });

    $http.get('/api/service', {
        params: {
            user: user_id
        }
    }).success(function(response) {
        $scope.services = response.data;
    });

    $http.get('/api/ticket', {
        params: {
            user: user_id
        }
    }).success(function(response) {
        $scope.tickets = response.data;
    });

});
