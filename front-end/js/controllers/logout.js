angular.module('practicaPHP01.controllers')
    /**
     * Cierra la sesión del usuario en el sistema.
     */
    .controller('LogoutController', ['$scope', 'UserService',
        function ($scope, UserService) {
            $scope.init = function() {
                // console.debug('Logout');
                $scope.error = null;
                $scope.success = null;

                logout();
            };

            var logout = function logout() {
                if (UserService.isLoggedIn()) {
                    UserService.logout(function(response) {
                        // console.debug(response.data);
                        $scope.success = true;
                    }, function(response) {
                        // console.warn(response);
                        $scope.error = true;
                    });
                } else {
                    // console.warn('User not logged in');
                    $scope.error = true;
                }
            };

            $scope.init();
        }]
    );
