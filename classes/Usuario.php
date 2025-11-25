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
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM {$this->tabla} WHERE nombre_usuario = :usuario LIMIT 1";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['usuario' => $usuario]);

        $resultado = $PDOStatement->fetchAll();
        return $resultado ? $resultado[0] : null;
    }

    /**
     * Obtener rol por ID de usuario
     * @param int $usuarioId
     * @return int|null
     */
    public static function obtenerRolPorUsuarioId(int $usuarioId): ?int
    {
        $conexion = Conexion::getConexion();
        $sql = "SELECT rol_id FROM rol_usuario WHERE usuario_id = :id LIMIT 1";
        $PDOStatement = $conexion->prepare($sql);
        $PDOStatement->execute(['id' => $usuarioId]);
        $resultado = $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

        $resultado = $PDOStatement->fetchAll();
        return $resultado ? $resultado[0]['rol_id'] : null;
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