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
            templateUrl: 'views/newservice.html',
            controller: 'NewServiceController'
        })
        .state('tickets', {
            url: '/tickets',
            templateUrl: 'views/tickets.html',
            controller: 'TicketsController'
        })
        .state('newticket', {
            url: '/newticket',
            templateUrl: 'views/newticket.html',
            controller: 'NewTicketController'
        })
        .state('ticketdetails', {
            url: '/ticket/:id',
            templateUrl: 'views/ticketdetails.html',
            controller: 'TicketDetailsController'
        });
})
.filter('dateTimeEU', function() {
    return function(raw_date) {
        if (raw_date == '0000-00-00 00:00:00') {
            return 'da confermare';
        } else {
            return moment(raw_date).format('DD/MM/YYYY - HH:mm');
        }
    };
})
.filter('dateEU', function() {
    return function(raw_date) {
        return moment(raw_date).format('DD/MM/YYYY');
    };
})
.filter('dateEULong', function() {
    return function(raw_date) {
        return moment(raw_date).format('ddd DD/MM/YYYY');
    };
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

Ticketisy.controller('TicketDetailsController', function($scope, $http, $stateParams) {
    var ticket_id = $stateParams.id;

    $http.get('/api/ticket/' + ticket_id, {
        params: {
            api_token: $scope.api_token
        }
    }).success(function(response) {
        $scope.ticket = response;
    });
});

//# sourceMappingURL=app.js.map
