<?php

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
    public function findByUsername(string $usuario): self{
        $conection = Conexion::getConexion();
        $query = "SELECT * FROM {$this->tabla} WHERE nombre_usuario = :usuario LIMIT 1";

        $PDOStatement = $conection->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['usuario' => $usuario]);

        $result = $PDOStatement->fetchAll();
        return $result ? $result[0] : null;
    }

    /**
     * Login de usuario
     * @param string $nombre_usuario
     * @param string $password
     * @return Usuario|null
     */
    public function login($nombre_usuario, $password): ?Usuario{
        $usuario = $this->findByUsername($nombre_usuario);
        if ($usuario && password_verify($password, $usuario->password)) {
            return $usuario;
        }
        return null;
    }
}