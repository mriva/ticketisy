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

    $scope.search = function(q) {
        $scope.get_list(q);
    }

    $scope.search_keypress = function(key, q) {
        if (key.which == 13) {
            $scope.search(q);
        }
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
