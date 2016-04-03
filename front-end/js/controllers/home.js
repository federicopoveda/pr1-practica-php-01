angular.module('practicaPHP01.controllers')
    /**
     * Despliega contenido restringido a usuarios con sesión.
     */
    .controller('HomeController', ['$scope', 'UserService', '$location',
        function ($scope, UserService, $location) {
            $scope.init = function() {
                // console.debug('Home');

                $scope.error = null;
                $scope.user = null;

                if (UserService.isLoggedIn()) {
                    $scope.user = UserService.getCurrentUser();
                } else {
                    $scope.error = true;
                }
            };

            $scope.init();
        }]);
