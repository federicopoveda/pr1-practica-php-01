angular.module('practicaPHP01.controllers')
    /**
     * Inicia la sesión del usuario en el sistema.
     */
    .controller('LoginController', ['$scope', '$location', 'UserService',
        function ($scope, $location, UserService) {
            $scope.init = function() {
                $scope.formMessages = null;

                $scope.user = {
                    email: null,
                    password: null
                };

                // Si el usuario ya tiene sesión activa
                if (UserService.isLoggedIn()) {
                    // console.debug('sesión activa');
                    $location.url('home');
                } else {
                    // Si el usuario acaba de registrarse
                    if (UserService.isNewUser()) {
                        $scope.formMessages = 'Bienvenido nuevo usuario';
                        // console.debug('nuevo usuario');
                    }
                }
            };

            $scope.login = function login(isValid) {
                if (isValid) {
                    UserService.login($scope.user, function(data) {
                        if (data.error) {
                            // console.warn(data.message);
                            $scope.formMessages = data.message;
                        } else {
                            $location.url('home');
                            // console.info(data.message);
                        }
                    }, function(data) {
                        // console.warn(data.message);
                        $scope.formMessages = data.message;
                    });
                } else {
                    $scope.formMessages = 'Please fill all the required data.';
                }
            };

            $scope.init();
        }]);
