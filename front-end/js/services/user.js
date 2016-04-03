angular.module('practicaPHP01.services')
    /**
     * Encargado de todas las operaciones relacionadas con los usuarios.
     */
    .service('UserService', ['$http', 'ClientStorage', function ($http, ClientStorage) {
        /**
         *
         * @param email
         * @param password
         */
        var login = function(email, password) {
            var result = {
                success: false,
                message: null
            };

            /**
             * TODO: Implementar
             * Pasos
             * - Asegúrese que tanto el email y el password estén definidos.
             * - Llame al backend con los datos del formulario (URL: `/back-end/user/login`).
             * - Basado en la respuesta, maneje los siguientes escenarios:
             *  - El email y password son correctos.
             *  - El email no está registrado.
             *  - El password es inválido.
             * - Si el primer escenario ocurre almacene un objeto usando `ClientStorage` que contenga el email y el
             * nombre del usuario en sesión.
             */

            return result;
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
         * @param user
         * @param success
         * @param error
         *
         */
        var register = function register(user, success, error) {
            return $http.post('back-end/user/register', {
                email: user.email,
                fullName: user.fullName,
                password: user.password,
                repeatPassword: user.repeatPassword
            }).then(success, error);
        };

        /**
         * Revisa si el usuario tiene una sesión activa.
         *
         * @returns {boolean}
         */
        var isLoggedIn = function isLoggedIn() {
            var result = {
                success: false,
                message: null
            };

            /**
             * TODO: Implementar
             * Pasos
             * - Verifique si existe algún dato en `ClientStorage`.
             * - Maneje los siguientes escenarios:
             *  - Si existe algún dato, el usuario tiene sesión activa.
             *  - No existe ningún dato, el usuario no cuenta con sesión activa.
             */

            return result;
        };

        /**
         * Obtiene información sobre el usuario actual.
         *
         * @returns {{email: string, fullName: string}}|null
         */
        var getCurrentUser = function getCurrentUser() {
            var user = {
                email: null,
                fullName: null
            };

            /**
             * TODO: Implementar
             * Pasos
             * - Verifique que el usuario tenga sesión activa.
             * - Maneje los siguientes escenarios:
             *  - El usuario tiene sesión activa, retorne un objeto con el email del usuario.
             *  - El usuario no tiene sesión activa, retorne `null`.
             */

            return user;
        };

        return {
            getCurrentUser: getCurrentUser,
            isLoggedIn: isLoggedIn,
            login: login,
            logout: logout,
            register: register
        };
    }]);
