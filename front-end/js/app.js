/**
 * Definición del módulo.
 */
angular.module('practicaPHP01', [
        'ngAnimate',
        'ngRoute',
        'ngMessages',
        'practicaPHP01.controllers',
        'practicaPHP01.directives',
        'practicaPHP01.filters',
        'practicaPHP01.services'
    ])
    /**
     * Configuración del enrutamiento.
     */
    .config(function($routeProvider) {
        $routeProvider
            .when('/', {
                controller: 'LoginController',
                templateUrl: 'front-end/partials/login.html'
            })
            .when('/register', {
                controller: 'RegisterController',
                templateUrl: 'front-end/partials/register.html'
            })
            .when('/home', {
                controller: 'HomeController',
                templateUrl: 'front-end/partials/home.html'
            })
            .when('/logout', {
                controller: 'LogoutController',
                templateUrl: 'front-end/partials/logout.html'
            })
            .otherwise({
                redirectTo: '/'
            })
    });

// Definimos todos los sub-módulos para evitar conflictos.
angular.module('practicaPHP01.controllers', []);
angular.module('practicaPHP01.services', []);
angular.module('practicaPHP01.directives', []);
angular.module('practicaPHP01.filters', []);
