angular.module('practicaPHP01.controllers')
    /**
     * Le permite a un usuario crear una nueva contraseña en el sistema.
     */
    .controller('RegisterController', ['$scope', '$location', 'UserService', 'ClientStorage',
        function ($scope, $location, UserService, ClientStorage) {
            $scope.init = function() {
                $scope.formMessages = null;

                $scope.user = {
                    email: null,
                    fullName: null,
                    password: null,
                    repeatPassword: null
                };
            };

            $scope.register = function register(isValid) {
                if (isValid) {
                    UserService.register($scope.user, function(response) {
                        var data = response.data;

                        if (data.error) {
                            $scope.formMessages = data.message;
                        } else {
                            $location.url('/');
                        }
                    }, function(data) {
                        $scope.formMessages = data;
                    });
                } else {
                    $scope.formMessages = 'Please fill all the required data.';
                }
            };

            $scope.init();
        }]);
