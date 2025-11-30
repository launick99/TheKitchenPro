<?php

/**
 * Clase que se ocupa de la autorizacion del usuario
 */
class Autorizacion{

    /**
     * Logea el usuario
     * @param string $usuario
     * @param string $password
     * @return bool|null retorna null si no se encontro el usuario, true/false si se pudo logear
     */
    public static function logIn(string $usuario, string $password): ?bool{
        $datosUsuario = Usuario::findByUsername($usuario);
        if(!$datosUsuario){
            return null;
        }
        if (password_verify($password, $datosUsuario->getPassword())) {
            $_SESSION['usuario'] = $datosUsuario;
            return true;
        }
        return false;
    }

    /**
     * Deslogea el usuario
     */
    public static function logOut(): void{
        if(isset($_SESSION['usuario'])){
            unset($_SESSION['usuario']);
        }
    }

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
        $conexion = Conexion::getConexion();
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
