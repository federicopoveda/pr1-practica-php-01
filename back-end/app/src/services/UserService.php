<?php

/**
 * UserService.php
 */

namespace App\Services;

class UserService {

    private $storage;
    private $isDBReady = true;

    /**
     * UserService constructor.
     */
    public function __construct() {
        // Verificación de la base de datos
        if ($this->isDBReady) {
            $this->storage = new StorageService();
        }
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

        // Verificamos que el email, sin espacios, tenga por lo menos 1 caracter
        if (strlen(trim($email)) > 0) {
            // Verificamos que el email tenga formato de email
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Verificamos que el password, sin espacios, tenga por lo menos 1 caracter
                if (strlen(trim($password)) > 0) {
                    // Si todo lo anterior tuvo éxito, iniciamos el query

                    // El query que vamos a ejecutar en la BD
                    $query = "SELECT id, email, full_name FROM usuarios WHERE email = :email AND password = :password LIMIT 1";

                    // Los parámetros de ese query
                    $params = [":email" => $email, ":password" => $password];

                    // Una vez que se cree la base de datos esté lista ésto se puede remover
                    if ($this->isDBReady) {
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
                        // La base de datos no está lista todavía
                        $result["message"] = "Database has not been setup yet.";
                        $result["error"] = true;
                    }
                } else {
                    // El password está en blanco
                    $result["message"] = "Password is required.";
                    $result["error"] = true;
                }
            } else {
                // El email no tiene formato de tal
                $result["message"] = "Email is invalid.";
                $result["error"] = true;
            }
        } else {
            // El email está en blanco
            $result["message"] = "Email is required.";
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
            if ($this->isValidEmail($email)) {
                // Si `password` es un string válido
                if ($this->isValidString($password)) {
                    // Si `$passwordConfirm` es un string válido
                    if ($this->isValidString($passwordConfirm)) {
                        // Si `$fullName` es un string válido
                        if ($this->isValidString($fullName)) {
                            // Si tanto `$password` como `$passwordConfirm` coinciden
                            if ($password == $passwordConfirm) {

                                // Si el email no ha sido usado
                                if ($this->isEmailAvailable($email)) {
                                    $query = "INSERT INTO usuarios (email, password, full_name) VALUES (:email, :password, :nombre)";

                                    // Los parámetros de ese query
                                    $params = [":email" => $email, ":password" => $password, ":nombre" => $fullName];

                                    // Lo ejecutamos
                                    $createAccountResult = $this->storage->insertQuery($query, $params);

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
     * Verifica si una cadena de texto puede ser considerada texto válido.
     *
     * @param string $stringToCheck
     * @return bool
     */
    private function isValidString($stringToCheck) {
        if (isset($stringToCheck)) {
            $trimmed = trim($stringToCheck);

            if (strlen($trimmed) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica si un stringToCheck es un email válido.
     *
     * @param stringToCheck $email
     * @return bool
     */
    private function isValidEmail($email) {
        return $this->isValidString($email) ? filter_var($email, FILTER_VALIDATE_EMAIL) : false;
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
    
    private function encryptPassword($password) {
        
    }

}
