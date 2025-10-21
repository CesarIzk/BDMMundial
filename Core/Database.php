<?php

namespace Core;

use PDO;

class Database
{
    public $connection;
    public $statement;

    public function __construct($config, $username = 'root', $password = '')
    {
        $dsn = 'mysql:' . http_build_query($config, '', ';');

        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

 public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);

        // --- INICIO DE LA CORRECCIÓN ---
        // Verificamos si hay parámetros antes de hacer el bucle
        if (!empty($params)) {
            $i = 1; // Contador para los placeholders posicionales (?)

            foreach ($params as $value) {
                
                // Determinamos el tipo de dato del parámetro
                $type = PDO::PARAM_STR; // Por defecto es string
                if (is_int($value)) {
                    $type = PDO::PARAM_INT; // ¡Esta es la corrección clave!
                }
                if (is_bool($value)) {
                    $type = PDO::PARAM_BOOL;
                }
                if (is_null($value)) {
                    $type = PDO::PARAM_NULL;
                }

                // Vinculamos el valor con su tipo de dato correcto
                $this->statement->bindValue($i, $value, $type);
                $i++; // Incrementamos el contador para el siguiente placeholder
            }
        }
        // --- FIN DE LA CORRECCIÓN ---

        // Ejecutamos la consulta SIN pasarle el array,
        // porque ya vinculamos los valores manualmente.
        $this->statement->execute();

        return $this;
    }

    public function get()
    {
        return $this->statement->fetchAll();
    }

    public function find()
    {
        return $this->statement->fetch();
    }

    public function findOrFail()
    {
        $result = $this->find();

        if (! $result) {
            abort();
        }

        return $result;
    }
}