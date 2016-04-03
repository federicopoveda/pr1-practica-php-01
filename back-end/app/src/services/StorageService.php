<?php

/**
 * StorageService.php
 * Interacción con la base de datos.
 */

namespace App\Services;

use \PDO;
use \PDOException;

class StorageService {

    // Instancia de la conexión a la BD, usada internamente.
    private $pdo;

    public function __construct() {
        // Incluimos el archivo que contiene las credenciales
        require("bd-credenciales.php");

        // Creamos una nueva conexión.
        $this->pdo = new PDO(
            "mysql:host={$config['db_host']};dbname={$config['db_name']}",
            $config['db_user'], $config['db_pass']
        );

        // Le solicitamos a la conexión que nos notifique de todos los errores.
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Ejecuta una sentencia de SQL.
     *
     * @param string $query
     * @param array $params
     *
     * @return array
     */
    public function query($query, $params=[]) {
        /**
         * Creamos un diccionario en donde se almacenará el resultado de la operación.
         * Los datos en sí, se regresarán bajo la llave `data` del diccionario, iniciada en null
         */
        $result = [
            "data" => null
        ];

        // Verificamos si la sentencia a ejecutar es un insert
        $isInsert = substr_count(strtoupper($query), "INSERT", 0, 7) > 0;

        try {
            // Preparamos la sentencia a ejecutar
            $stmt = $this->pdo->prepare($query);

            // Asignamos los parámetros a la sentencia
            $stmt->execute($params);

            /**
             * Si intentamos recorrer el resultado de una sentencia `INSERT` dispararemos un error,
             * debemos verificar que tipo de operación queremos realizar antes de ejecutarlo.
             */
            if ($isInsert) {
                // Retornamos la cantidad de elementos afectados
                $result["data"]["count"] = $stmt->rowCount();
                // Junto con el ID del elemento agregado
                $result["data"]["id"] = $this->pdo->lastInsertId();
            } else {
                // Ejecutamos la sentencia
                while ($content = $stmt->fetch()) {
                    // Vaciando el contenido de cada fila dentro de `data` en el diccionario `result`.
                    $result["data"][] = $content;
                }
            }
        } catch (PDOException $e) {
            // En caso de que algo saliera mal con nuestro intento de conexión, el mensaje se envia de vuelta al
            // servicio que consumió este método.
            $result["error"] = true;
            $result["message"] = $e->getMessage();
        }

        // Retorne el arreglo de resultado a quien sea que lo haya llamado.
        return $result;
    }

}
