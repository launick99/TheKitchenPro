<?php

require_once 'Model.php';

/**
 * Clase Usuario
 */
class Usuario
{
    protected $tabla = 'usuarios';

    public $id;
    public $nombre_usuario; // Nombre de usuario
    public $email;
    public $password; // Hasheada

    /**
     * Buscar usuario por nombre de usuario
     * @param string $usuario
     * @return Usuario|null
     */
    public function findByUser(string $usuario): self{
        $conection = (new Connection())->getConection();
        $query = "SELECT * FROM {$this->tabla} WHERE nombre_usuario = :usuario LIMIT 1";

        $PDOStatement = $conection->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['usuario' => $usuario]);

        $result = $PDOStatement->fetchAll();
        return $result ? $result[0] : null;
    }

    // Verificar login
    public function login($nombre_usuario, $password): ? Usuario{
        $usuario = $this->findByUser($nombre_usuario);
        // die( password_hash('123', '2y') );
        if ($usuario && password_verify($password, $usuario->password)) {
            return $usuario;
        }
        return null;
    }
}