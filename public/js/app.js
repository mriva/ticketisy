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
        .state('assignedtickets', {
            url: '/assigned-tickets',
            templateUrl: 'views/assigned-tickets.html',
            controller: 'AssignedTicketsController'
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
            controller: 'UsersController',
            resolve: {
                role: function() {
                    return 'user';
                }
            }
        })
        .state('userdetails', {
            url: '/user/:id',
            templateUrl: 'views/userdetails.html',
            controller: 'UserDetailsController'
        })
        .state('technicians', {
            url: '/technicians',
            templateUrl: 'views/technicians.html',
            controller: 'UsersController',
            resolve: {
                role: function() {
                    return 'technician';
                }
            }
        })
        .state('techniciandetails', {
            url: '/technician/:id',
            templateUrl: 'views/techniciandetails.html',
            controller: 'TechnicianDetailsController'
        })
        .state('newtechnician', {
            url: '/newtechnician',
            templateUrl: 'views/newtechnician.html',
            controller: 'NewTechnicianController'
        });
})
.config(function($httpProvider) {
    $httpProvider.interceptors.push(function ($q, $injector) {
        return {
            'request': function(config) {
                if (config.url.match(/^\/api\//)) {
                    if (typeof(config.params) === 'undefined') {
                        config.params = {};
                    }
                    config.params.api_token = App.api_token;
                }
                return config;
            },
            'responseError': function(response) {
                if (response.status != '401') {
                    return $q.reject(response);
                }

                var $modal = $injector.get('$uibModal');
                $modal.open({
                    templateUrl: 'views/modal-unauthorized.html'
                });

                var $state = $injector.get('$state');
                $state.go('home');

                return $q.reject(response);
            }

        }
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
});

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

Ticketisy.controller('TicketsController', function($scope, $http) {
    $http.get('/api/ticket', {
        params: {
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
            status: 'pending'
        }
    }).success(function(response) {
        $scope.tickets = response.data;
        $scope.empty = !response.data.length;
    });
});

Ticketisy.controller('AssignedTicketsController', function($scope, $http) {
    $http.get('/api/ticket', {
        params: {
            status: 'assigned'
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
    
    $http.get('/api/service/' + service_id).success(function(response) {
        $scope.service = response;
        $scope.newticket.service_id = response.id;
    });

    $http.get('/api/department').success(function(response) {
        $scope.departments = response.data;
    });

    var postdata = $scope.newticket;

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
    $scope.App = App;

    $scope.get_ticket = function() {
        $http.get('/api/ticket/' + ticket_id).success(function(response) {
            $scope.ticket = response;
            $scope.get_technicians();
        });
    }

    $scope.get_technicians = function() {
        if ($scope.role == 'admin') {
            $http.get('/api/user', {
                params: {
                    role: 'technician',
                    department: $scope.ticket.department_id
                }
            }).success(function(response) {
                $scope.technicians = response.data;
            });
        }
    }

    $scope.assign = function() {
        var postdata = {
            ticket_id: $scope.ticket.id,
            action: 'assignee',
            value: $scope.newtechnician
        }

        $http.post('/api/ticketevent', postdata).success(function(response) {
            $scope.newtechnician = null;
            $scope.get_ticket();
        });
    }

    $scope.$watch('newpriority', function(new_value, old_value) {
        if (typeof(new_value) !== 'undefined') {
            $scope.priority_changed = true;
        }
    });

    $scope.save_priority = function() {
        var postdata = {
            ticket_id: $scope.ticket.id,
            action: 'priority',
            value: $scope.newpriority
        }

        $http.post('/api/ticketevent', postdata).success(function(response) {
            $scope.priority_changed = false;
            $scope.get_ticket();
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
            ticket_id: ticket_id,
            action: 'comment',
            value: $scope.newcomment
        }

        $http.post('/api/ticketevent', postdata).success(function(response) {
            $scope.newcomment = '';
            $scope.comment_open = false;
            $scope.get_ticket();
        });
    }

    $scope.close = function() {
        $scope.close_box = true;
    }

    $scope.close_cancel = function() {
        $scope.closemessage = '';
        $scope.close_box = false;
    }

    $scope.close_confirm = function() {
        var postdata = {
            ticket_id: ticket_id,
            action: 'close',
            value: $scope.closemessage
        }

        $http.post('/api/ticketevent', postdata).success(function(response) {
            $scope.closemessage = '';
            $scope.close_box = false;
            $scope.get_ticket();
        });
    }

    $scope.get_ticket();
});

Ticketisy.controller('MyTicketsController', function($scope, $http) {
    $http.get('/api/ticket', {
        params: {
            status: 'assigned',
            technician: 'me'
        }
    }).success(function(response) {
        $scope.tickets = response.data;
        $scope.empty = !response.data.length;
    });
});

Ticketisy.controller('UsersController', function($scope, $http, role) {
    $scope.get_list = function(search) {
        var args = {
            params: {
                role: role
            }
        }

        if (search) {
            args.params.search = search;
        }

        $http.get('/api/user', args).success(function(response) {
            $scope.users = response.data;
        });
    }

    $scope.search = function() {
        console.log($scope.searchuser);
        $scope.get_list($scope.searchuser);
    }

    $scope.reset = function() {
        $scope.searchuser = null;
        $scope.get_list();
    }

    $scope.get_list();
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

//# sourceMappingURL=app.js.map
