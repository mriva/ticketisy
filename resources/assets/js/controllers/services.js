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

Ticketisy.controller('NewServiceController', function($scope, $http, $state) {
    $scope.newservice = {};
    $scope.errors = {};

    $http.get('/api/product', {
        params: {
            api_token: $scope.api_token
        }
    }).success(function(response) {
        $scope.products = response.data;
    });

    var postdata = $scope.newservice;
    postdata.api_token = $scope.api_token;

    $scope.save = function() {
        $http.post('/api/service', postdata).success(function(response) {
            $state.go('services');
        }).error(function(response) {
            $scope.errors = response;
        });
    }
});
