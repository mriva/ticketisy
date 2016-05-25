Ticketisy.controller('ServicesController', function($scope, $http) {
    $http.get('/api/service').success(function(response) {
        $scope.services = response.data;
        $scope.empty = !response.data.length;
    });
});

Ticketisy.controller('NewServiceController', function($scope, $http, $state) {
    $scope.newservice = {};
    $scope.errors = {};

    $http.get('/api/product').success(function(response) {
        $scope.products = response.data;
    });

    var postdata = $scope.newservice;

    $scope.save = function() {
        $http.post('/api/service', postdata).success(function(response) {
            $state.go('services');
        }).error(function(response) {
            $scope.errors = response;
        });
    }
});

Ticketisy.controller('ServiceDetailsController', function($scope, $http, $stateParams) {
    var service_id = $stateParams.id

    $http.get('/api/service/' + service_id).success(function(response) {
        $scope.service = response;
    });

    $http.get('/api/ticket', {
        params: {
            service: service_id
        }
    }).success(function(response) {
        $scope.tickets = response.data;
    });
});
