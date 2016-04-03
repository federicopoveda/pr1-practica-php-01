angular.module('practicaPHP01.services')
    /**
     * Encargado de todas las operaciones relacionadas con los usuarios.
     */
    .service('UserService', ['$http', 'ClientStorage', function ($http, ClientStorage) {
        var UserKey = 'practicaPHP01__userData',
            NewUserKey = 'practicaPHP01__isNewUser';

        /**
         * Inicia la sesión del usuario en el sistema.
         *
         * @param user objeto con email y password
         * @param success callback efectuado cuando la operación tiene éxito
         * @param error callback efectuado con la operación falla
         */
        var login = function(user, success, error) {
            return $http.post('back-end/user/login', {
                email: user.email,
                password: user.password
            }).then(function(response) {
                if (response.data.error) {
                    error(response.data);
                } else {
                    ClientStorage.put(UserKey, response.data.user);
                    success(response.data);
                }
            }, function(response) {
                error(response.data);
            });
        };

        /**
         * Cierra la sesión del usuario.
         *
         * @returns {boolean}
         */
        var logout = function logout() {
            var result = {
                success: false,
                message: null
            };

            /**
             * TODO: Implementar
             * Pasos
             * - Elimine la información del usuario de `ClientStorage`.
             * - Llame al back-end para que cerrar la sesión en el back-end (URL: `/back-end/user/logout`).
             * - Verifique que se eliminaron los datos correctamente.
             * - Maneje los siguientes escenarios:
             *  - No existían datos.
             *  - Los datos fueron correctamente eliminados.
             *  - Los datos
             * - En los casos 1 y 2, retorne `true` como valor de esta función.
             * - Para el caso 3, retorne `false`.
             */

            return result;
        };

        /**
         * Registra un usuario en el sistema.
         *
         * @param user objeto con información completa del usuario, email, fullName, password, repeatPassword
         * @param success callback efectuado cuando la operación tiene éxito
         * @param error callback efectuado con la operación falla
         *
         */
        var register = function register(user, success, error) {
            return $http.post('back-end/user/register', {
                email: user.email,
                fullName: user.fullName,
                password: user.password,
                repeatPassword: user.repeatPassword
            }).then(function(response) {
                setAsNewUser();
                success(response);
            }, error);
        };

        /**
         * Revisa si el usuario tiene una sesión activa.
         *
         * @returns {boolean}
         */
        var isLoggedIn = function isLoggedIn() {
            return !!ClientStorage.get(UserKey);
        };

        /**
         * Obtiene información sobre el usuario actual.
         *
         * @returns {{email: string, fullName: string}}|null
         */
        var getCurrentUser = function getCurrentUser() {
            return isLoggedIn() ? ClientStorage.get(UserKey) : null;
        };

        /**
         * Marcamos localmente al usuario como `nuevo` en el sistema.
         */
        var setAsNewUser = function setAsNewUser() {
            ClientStorage.put(NewUserKey, {isNew: true});
        };

        /**
         * Verifica si el usuario acaba de terminar de registrarse en el sistema.
         * Después de la primera verificación, se elimina la bandera del usuario como nuevo.
         *
         * @returns {boolean}
         */
        var isNewUser = function isNewUser() {
            if (ClientStorage.get(NewUserKey)) {
                ClientStorage.erase(NewUserKey);
                return true;
            }

            return false;
        };

        return {
            getCurrentUser: getCurrentUser,
            isLoggedIn: isLoggedIn,
            isNewUser: isNewUser,
            login: login,
            logout: logout,
            register: register
        };
    }]);
