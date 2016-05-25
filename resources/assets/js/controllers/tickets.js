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
