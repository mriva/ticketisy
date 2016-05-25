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

Ticketisy.controller('NewTechnicianController', function($scope, $http, $state) {
    $scope.newtechnician = {};
    $scope.errors = {};
    $scope.checkboxes = {};

    $http.get('/api/department').success(function(response) {
        $scope.departments = response.data;
    });

    $scope.save = function() {
        var postdata = $scope.newtechnician;
        postdata.role = 'technician';
        postdata.departments = _.keys($scope.checkboxes);

        $http.post('/api/user', postdata).success(function(response) {
            $state.go('technicians');
        }).error(function(response) {
            $scope.errors = response;
        });
    }
});
