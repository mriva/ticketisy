Ticketisy.controller('TechniciansController', function($scope, $http) {
    $http.get('/api/user', {
        params: {
            role: 'technician',
        }
    }).success(function(response) {
        $scope.users = response.data;
    });
});

Ticketisy.controller('TechnicianDetailsController', function($scope, $http, $stateParams) {
    var user_id = $stateParams.id;

    $http.get('/api/user/' + user_id).success(function(response) {
        $scope.user = response;
    });

    $http.get('/api/department', {
        params: {
            technician: user_id
        }
    }).success(function(response) {
        $scope.departments = response.data;
    });

    $http.get('/api/ticket', {
        params: {
            technician: user_id
        }
    }).success(function(response) {
        $scope.tickets = response.data;
    });
});
