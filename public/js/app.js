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
        .state('tickets', {
            url: '/tickets',
            templateUrl: 'views/tickets.html',
            controller: 'TicketsController'
        });
});

Ticketisy.controller('HomeController', function($scope, $http) {
    console.log($scope.authLevel);
});

Ticketisy.controller('ServicesController', function($scope, $http) {
    
});

Ticketisy.controller('TicketsController', function($scope, $http) {
    
});

//# sourceMappingURL=app.js.map
