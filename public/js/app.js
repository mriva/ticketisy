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
        .state('servicedetails', {
            url: '/service/:id',
            templateUrl: 'views/servicedetails.html',
            controller: 'ServiceDetailsController'
        })
        .state('tickets', {
            url: '/tickets',
            templateUrl: 'views/tickets.html',
            controller: 'TicketsController'
        })
        .state('pendingtickets', {
            url: '/pending-tickets',
            templateUrl: 'views/pending-tickets.html',
            controller: 'PendingTicketsController'
        })
        .state('newticket', {
            url: '/newticket/:service_id',
            templateUrl: 'views/newticket.html',
            controller: 'NewTicketController'
        })
        .state('ticketdetails', {
            url: '/ticket/:id',
            templateUrl: 'views/ticketdetails.html',
            controller: 'TicketDetailsController'
        })
        .state('mytickets', {
            url: '/my-tickets',
            templateUrl: 'views/my-tickets.html',
            controller: 'MyTicketsController'
        })
        .state('users', {
            url: '/users',
            templateUrl: 'views/users.html',
            controller: 'UsersController'
        });
})
.filter('dateTimeEU', function() {
    return function(raw_date) {
        if (raw_date == '0000-00-00 00:00:00') {
            return 'da confermare';
        } else {
            return moment(raw_date).format('DD/MM/YYYY HH:mm');
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

Ticketisy.controller('ServiceDetailsController', function($scope, $http, $stateParams) {
    var service_id = $stateParams.id

    $http.get('/api/service/' + service_id, {
        params: {
            api_token: $scope.api_token
        }
    }).success(function(response) {
        $scope.service = response;
    });

    $http.get('/api/ticket', {
        params: {
            api_token: $scope.api_token,
            service: service_id,
        }
    }).success(function(response) {
        $scope.tickets = response.data;
    });
});

Ticketisy.controller('TicketsController', function($scope, $http) {
    $http.get('/api/ticket', {
        params: {
            api_token: $scope.api_token,
            status: 'pending,assigned'
        }
    }).success(function(response) {
        $scope.tickets = response.data;
        $scope.empty = !response.data.length;
    });
});

Ticketisy.controller('PendingTicketsController', function($scope, $http) {
    $http.get('/api/ticket', {
        params: {
            api_token: $scope.api_token,
            status: 'pending'
        }
    }).success(function(response) {
        $scope.tickets = response.data;
        $scope.empty = !response.data.length;
    });
});

Ticketisy.controller('NewTicketController', function($scope, $http, $state, $stateParams) {
    var service_id = $stateParams.service_id;

    $scope.newticket = {
        priority: 'normal',
    };
    $scope.errors = {};
    
    $http.get('/api/service/' + service_id, {
        params: {
            api_token: $scope.api_token
        }
    }).success(function(response) {
        $scope.service = response;
        $scope.newticket.service_id = response.id;
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
            $state.go('servicedetails', { id: $scope.service.id });
        }).error(function(response) {
            $scope.errors = response;
        });
    }
});

Ticketisy.controller('TicketDetailsController', function($scope, $http, $stateParams) {
    var ticket_id = $stateParams.id;
    $scope.comment_open = false;
    $scope.newcomment = '';

    $scope.get_ticket = function() {
        $http.get('/api/ticket/' + ticket_id, {
            params: {
                api_token: $scope.api_token
            }
        }).success(function(response) {
            $scope.ticket = response;
        });
    }

    $scope.comment = function() {
        $scope.comment_open = true;
    }

    $scope.comment_close = function() {
        $scope.newcomment = '';
        $scope.comment_open = false;
    }

    $scope.comment_save = function() {
        var postdata = {
            api_token: $scope.api_token,
            ticket_id: ticket_id,
            action: 'comment',
            value: $scope.newcomment
        }

        $http.post('/api/ticketevent', postdata).success(function(response) {
            $scope.comment_open = false;
            $scope.get_ticket();
        });
    }

    $scope.get_ticket();
});

Ticketisy.controller('MyTicketsController', function($scope, $http) {
    $http.get('/api/ticket', {
        params: {
            api_token: $scope.api_token,
            status: 'assigned',
            technician: 'me',
        }
    }).success(function(response) {
        $scope.tickets = response.data;
        $scope.empty = !response.data.length;
    }).error(function(response) {
        console.log('error');
    });
});

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

//# sourceMappingURL=app.js.map
