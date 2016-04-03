<?php

/**
 * UserController.php
 * Responsabilidades de cada función pública:
 * - Tomar las peticiones desde AngularJS.
 * - Preparar y enviarlas al servicio en PHP.
 * - Recibir las respuesta del servicio en PHP.
 * - Prepararlas y enviarlas de vuelta a AngularJS.
 */

namespace App\Controllers;

use App\Services\UserService;
use Slim\Http\Request;

class UserController {

    private $userService;
    private $nombreCookie = "loggedIn";

    /**
     * UserController constructor.
     */
    public function __construct() {
        $this->userService = new UserService();
    }

    /**
     * Intermediario entre el Front-End y el servicio.
     *
     * @param Request $request
     *
     * @return []
     */
    public function login($request) {
        $result = [];

        /**
         * El contenido de las peticiones tipo `POST` se obtiene llamando a `getParsedBody`.
         * El valor de retorno de esa función es un diccionario con el contenido del formulario.
         */
        $formData = $request->getParsedBody();
        $email = null;
        $password = null;

        // Verificamos que efectivamente exista una entrada de email
        if (array_key_exists("email", $formData)) {
            $email = $formData["email"];
        }

        // Verificamos que efectivamente exista una entrada de password
        if (array_key_exists("password", $formData)) {
            $password = $formData["password"];
        }

        $loginResult = $this->userService->login($email, $password);

        if (array_key_exists("error", $loginResult)) {
            $result["error"] = true;
        } else {
            /**
             * Si el usuario inició sesión, creamos un cookie llamado `loggedIn` y le asignamos el valor de true.
             * Este cookie se debe expirar en caso de cerrar sesión.
             * http://php.net/manual/en/function.setcookie.php
             */
            setcookie($this->nombreCookie, true, time()+3600);
            $result["user"] = $loginResult["user"];
        }

        $result["message"] = $loginResult["message"];

        // El array creado en ese método se envía como de vuelta al enrutador.
        return $result;
    }

    /**
     * Cierra la sesión del usuario del lado del back-end.
     *
     * @param Request $request
     *
     * @return string []
     */
    public function logout($request) {
        $result = [];

        // Verificamos si el usuario tenía un cookie en primer lugar
        if (array_key_exists($this->nombreCookie, $_COOKIE)) {
            $result["message"] = "User was logged out";
            // Si lo tenía, lo expiramos
            setcookie($this->nombreCookie, true, time()-10);
        } else {
            // Si no, retornamos un error, el usuario accedió al logout sin iniciar sesión
            $result["error"] = true;
            $result["message"] = "User never logged in";
        }

        return $result;
    }

    /**
     * Registra un nuevo usuario en el sistema.
     *
     * @param Request $request
     *
     * @return string []
     */
    public function register($request) {
        $result = [];
        $formData = $request->getParsedBody();
        $fullName = null;
        $email = null;
        $password = null;
        $repeatPassword = null;

//        LOG
//        error_log(print_r($formData, true), 3, "error.log");

        if (array_key_exists("email", $formData)) {
            $email = $formData["email"];
        }

        if (array_key_exists("fullName", $formData)) {
            $fullName = $formData["fullName"];
        }

        if (array_key_exists("password", $formData)) {
            $password = $formData["password"];
        }

        if (array_key_exists("repeatPassword", $formData)) {
            $repeatPassword = $formData["repeatPassword"];
        }

        $registerResult = $this->userService->register($email, $password, $repeatPassword, $fullName);

        if (array_key_exists("error", $registerResult)) {
            $result["error"] = true;
        }

        $result["message"] = $registerResult["message"];

        return $result;
    }

}
