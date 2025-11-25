<?php

class UserRol
{
    protected $tabla = 'rol_usuario';

    public $id;
    public $user_id;
    public $rol;

    const ROL_ADMIN = 1;

    /**
     * Verifica si un usuario tiene un rol específico
     * @param int $user_id ID del usuario
     * @param int $rol ID del rol
     * @return bool Verdadero si el usuario tiene el rol, falso en caso contrario
     */
    public function hasRol(int $user_id, int $rol): bool
    {
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("SELECT * FROM {$this->tabla} WHERE user_id = :user AND rol_id = :rol LIMIT 1");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['user_id' => $user_id, 'rol' => $rol]);

        return count($PDOStatement->fetch()) > 0;
    }
}