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
    $scope.App = App;

    $scope.get_ticket = function() {
        $http.get('/api/ticket/' + ticket_id, {
            params: {
                api_token: $scope.api_token
            }
        }).success(function(response) {
            $scope.ticket = response;
        });
    }

    if ($scope.role == 'admin') {
        $http.get('/api/user', {
            params: {
                role: 'technician'
            }
        }).success(function(response) {
            $scope.technicians = response;
        });
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
