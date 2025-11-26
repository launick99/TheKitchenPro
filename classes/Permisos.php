<?php

class Permisos
{
    protected $tabla =  "rol_vista";

    /**
     * Verifica si un usuario puede ver una vista determinada
     *
     * @param Usuario|null $usuario
     * @param Vista $vista
     * @return bool
     */
    public static function usuarioPuedeVer(?Usuario $usuario, Vista $vista): bool
    {
        if (!$vista->getRestringido()) {
            return true;
        }
        if (!$usuario) {
            return false;
        }
        if (!$usuario->getRolId()) {
            return false;
        }
        return self::rolPuedeVer($usuario->getRolId(), $vista->getId());
    }


    /**
     * Verifica si un rol tiene permitido acceder a una vista
     *
     * @param int $rolId
     * @param int $vistaId
     * @return bool
     */
    public static function rolPuedeVer(int $rolId, int $vistaId): bool
    {
        $conexion = (new Conexion())->getConexion();

        $query = "
            SELECT permitido 
            FROM rol_vista
            WHERE rol_id = :rol_id 
            AND vista_id = :vista_id
            LIMIT 1
        ";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute([
            'rol_id'   => $rolId,
            'vista_id' => $vistaId
        ]);

        $resultado = $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $resultado ?? false;
    }
}
