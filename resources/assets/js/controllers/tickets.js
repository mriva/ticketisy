Ticketisy.controller('TicketsController', function($scope, $http) {
    $http.get('/api/ticket', {
        params: {
            api_token: $scope.api_token
        }
    }).success(function(response) {
        $scope.tickets = response.data;
        $scope.empty = !response.data.length;
    }).error(function(response) {
        console.log('error');
    });
});

Ticketisy.controller('NewTicketController', function($scope, $http, $state) {
    $scope.newticket = {
        priority: 'normal',
    };
    $scope.errors = {};
    
    $http.get('/api/service', {
        params: {
            api_token: $scope.api_token
        }
    }).success(function(response) {
        $scope.services = response.data;
    }).error(function(response) {
        console.log('error');
    });

    $http.get('/api/department', {
        params: {
            api_token: $scope.api_token
        }
    }).success(function(response) {
        $scope.departments = response.data;
    }).error(function(response) {
        console.log('error');
    });

    var postdata = $scope.newticket;
    postdata.api_token = $scope.api_token;

    $scope.save = function() {
        $http.post('/api/ticket', postdata).success(function(response) {
            $state.go('tickets');
        }).error(function(response) {
            $scope.errors = response;
        });
    }
});

Ticketisy.controller('TicketDetailsController', function($scope, $http, $state) {
    
});
