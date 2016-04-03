<?php

/**
 * UserService.php
 */

namespace App\Services;

class UserService {

    private $storage;
    private $validation;

    /**
     * UserService constructor.
     */
    public function __construct() {
        $this->storage = new StorageService();
        $this->validation = new ValidationService();
    }

    /**
     * Encargado de iniciar la sesión del usuario.
     *
     * @param string $email
     * @param string $password
     *
     * @return array
     */
    public function login($email, $password) {
        $result = [];

        // Verificamos que el email sea válido
        if ($this->validation->isValidEmail($email)) {
            // Verificamos que el password, sin espacios, tenga por lo menos 1 caracter
            if ($this->validation->isValidString($password)) {
                // Si todo lo anterior tuvo éxito, iniciamos el query

                // El query que vamos a ejecutar en la BD
                $query = "SELECT id, email, full_name FROM usuarios WHERE email = :email AND password = :password LIMIT 1";

                // Encriptamos el password
                $encryptedPassword = $this->getProtectedPassword($password);

                // Los parámetros de ese query
                $params = [":email" => $email, ":password" => $encryptedPassword];

                // El resultado de de ejecutar la sentencia se almacena en la variable `result`
                $result = $this->storage->query($query, $params);

                // Si la setencia tiene por lo menos una fila, quiere decir que encontramos a nuestro usuario
                if (count($result['data']) > 0) {
                    // Almacenamos el usuario en la variable `user`
                    $user = $result['data'][0];

                    // Definimos nuestro mensaje de éxito
                    $result["message"] = "User found.";

                    // Enviamos de vuelta a quien consumió el servicio datos sobre el usuario solicitado
                    $result["user"] = [
                        "id" => $user["id"],
                        "email" => $user["email"],
                        "fullName" => $user["full_name"]
                    ];
                } else {
                    // No encontramos un usuario con ese email y password
                    $result["message"] = "Invalid credentials.";
                    $result["error"] = true;
                }
            } else {
                // El password está en blanco
                $result["message"] = "Password is required.";
                $result["error"] = true;
            }
        } else {
            // El email está en blanco
            $result["message"] = "Email is invalid.";
            $result["error"] = true;
        }

        return $result;
    }

    /**
     * Registra un nuevo usuario en el sistema.
     *
     * @param string $email
     * @param string $password
     * @param string $passwordConfirm
     * @param string $fullName
     *
     * @return array
     */
    public function register($email, $password, $passwordConfirm, $fullName) {
        $result = [];

        // Verificamos que efectivamente vengan todos los datos
        if (isset($email, $password, $passwordConfirm, $fullName)) {
            $email = trim($email);
            $password = trim($password);
            $passwordConfirm = trim($passwordConfirm);
            $fullName = trim($fullName);

            // Si nuestro correo es válido
            if ($this->validation->isValidEmail($email)) {
                // Si `password` es un string válido
                if ($this->validation->isValidString($password)) {
                    // Si `$passwordConfirm` es un string válido
                    if ($this->validation->isValidString($passwordConfirm)) {
                        // Si `$fullName` es un string válido
                        if ($this->validation->isValidString($fullName)) {
                            // Si tanto `$password` como `$passwordConfirm` coinciden
                            if ($password == $passwordConfirm) {

                                // Si el email no ha sido usado
                                if ($this->isEmailAvailable($email)) {

                                    $query = "INSERT INTO usuarios (email, password, full_name) VALUES (:email, :password, :nombre)";

                                    // Encriptamos el password
                                    $encryptedPassword = $this->getProtectedPassword($password);

                                    // Los parámetros de ese query
                                    $params = [":email" => $email, ":password" => $encryptedPassword, ":nombre" => $fullName];

                                    // Lo ejecutamos
                                    $createAccountResult = $this->storage->query($query, $params);

//                                    LOG
//                                    error_log(print_r($createAccountResult, true), 3, "error.log");

                                    if ($createAccountResult["data"]["count"] == 1) {
                                        $result["message"] = "yay!";
                                    } else {
                                        $result["error"] = true;
                                        $result["message"] = "Something is up";
                                    }
                                } else {
                                    $result["error"] = true;
                                    $result["message"] = "Email is unavailable";
                                }
                            } else {
                                $result["error"] = true;
                                $result["message"] = "Passwords don't match";
                            }
                        } else {
                            $result["error"] = true;
                            $result["message"] = "Full name is invalid";
                        }
                    } else {
                        $result["error"] = true;
                        $result["message"] = "Password confirm is invalid";
                    }
                } else {
                    $result["error"] = true;
                    $result["message"] = "Password is invalid";
                }
            } else {
                $result["error"] = true;
                $result["message"] = "Email is invalid";
            }
        } else {
            $result["error"] = true;
            $result["message"] = "All fields are required";
        }

        return $result;
    }

    /**
     * Verifica si un email está disponible para ser utilizado en el sistema.
     *
     * @param string $email
     * @return bool
     */
    private function isEmailAvailable($email) {
        // El query que vamos a ejecutar en la BD
        $query = "SELECT COUNT(*) AS count FROM usuarios WHERE email = :email";

        // Los parámetros de ese query
        $params = [":email" => $email];

        // Lo ejecutamos
        $result = $this->storage->query($query, $params);

//        LOG
//        error_log(print_r($result, true), 3, "error.log");

        // El resultado esperado de la cuenta es cero
        return $result["data"][0]["count"] == 0;
    }

    /**
     * Enmascara la contraseña brindada para evitar almacenar las contraseñas en texto plano en la base de datos.
     *
     * @param $password
     * @return string
     */
    private function getProtectedPassword($password) {
        /**
         * Primer intento: duplicar la contraseña.
         * Entrada: password
         * Salida: passwordpassword
         */
        // Primer intento, copiar el password
        $finalPassword = $password . $password;

        return $finalPassword;
    }

}
