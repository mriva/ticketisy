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
