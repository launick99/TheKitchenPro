<?php

/**
 * Clase Usuario
 */
class Usuario
{
    protected $tabla = 'usuarios';

    protected $id;
    protected $nombre_completo;
    protected $nombre_usuario; // Nombre de usuario
    protected $mail;
    protected $password; // Hasheada
    protected $rol_id;

    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getId(): int{
        return $this->id;
    }

    public function getNombre(): ?string{
        return $this->nombre_completo;
    }

    public function getNombreUsuario(): string{
        return $this->nombre_usuario;
    }

    public function getMail(): ?string{
        return $this->mail;
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function getRolId(): ?int{
        return $this->rol_id;
    }
    /* ----------------------------------
    |  Setters
    +---------------------------------- */
    public function setId($id){
        $this->id = $id;
    }

    public function setNombre(string $nombre){
        return $this->nombre_completo = $nombre;
    }

    public function setNombreUsuario($nombre_usuario){
        $this->nombre_usuario = $nombre_usuario;
    }

    public function setMail($mail){
        $this->mail = $mail;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function setRolId($rol_id){
        $this->rol_id = $rol_id;
    }

    /**
     * Buscar usuario por nombre de usuario
     * @param string $usuario
     * @return Usuario|null
     */
    public static function findByUsername(string $usuario): ?self{
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM usuarios WHERE nombre_usuario = :usuario LIMIT 1";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['usuario' => $usuario]);

        return $PDOStatement->fetch() ?: null;
    }
    /**
     * retorna si el usuario tiene un rol asignado
     */
    public function tieneRol(): bool{
        return $this->getRolId() !== null;
    }
}