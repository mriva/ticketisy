var Ticketisy = angular.module('ticketisy', ['ui.bootstrap', 'ui.router'])
.config(function($stateProvider, $httpProvider) {
    $stateProvider
        .state('home', {
            url: '',
            templateUrl: 'views/home.html',
            controller: 'HomeController'
        })
        .state('services', {
            url: '/services',
            templateUrl: 'views/services.html',
            controller: 'ServicesController'
        })
        .state('newservice', {
            url: '/newservice',
            templateUrl: 'views/new-service.html',
            controller: 'ServicesController'
        })
        .state('tickets', {
            url: '/tickets',
            templateUrl: 'views/tickets.html',
            controller: 'TicketsController'
        });
});

Ticketisy.controller('HomeController', function($scope, $http) {
    console.log($scope.role);
});

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

Ticketisy.controller('TicketsController', function($scope, $http) {
    
});

//# sourceMappingURL=app.js.map
