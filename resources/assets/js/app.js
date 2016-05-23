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
            controller: 'UsersController'
        })
        .state('userdetails', {
            url: '/user/:id',
            templateUrl: 'views/userdetails.html',
            controller: 'UserDetailsController'
        })
        .state('technicians', {
            url: '/technicians',
            templateUrl: 'views/technicians.html',
            controller: 'TechniciansController'
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
    $httpProvider.interceptors.push(function ($q) {
        return {
            'request': function(config) {
                if (config.url.match(/^\/api\//)) {
                    // config.url = config.url + '?api_token=' + App.api_token;
                    if (typeof(config.params) === 'undefined') {
                        config.params = {};
                    }
                    config.params.api_token = App.api_token;
                }
                return config;
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
