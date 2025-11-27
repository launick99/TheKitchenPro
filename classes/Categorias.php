<?php

/**
 * Clase que representa las categorías de productos.
 */
class Categorias {

    protected $tabla = 'categorias';

    protected $id;
    protected $nombre;
    protected $activa;
    protected $descripcion;

    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getId(): ?int {
        return $this->id;
    }

    public function getNombre(): ?string {
        return $this->nombre;
    }

    public function getDescripcion(): ?string {
        return $this->descripcion;
    }

    public function getActiva(): ?bool {
        return $this->activa;
    }

    /* ----------------------------------
    |  Setters
    +---------------------------------- */
    public function setNombre(string $nombre){
        $this->nombre = $nombre;
    }

    public function setActiva(bool $activa){
        $this->activa = $activa;
    }

    public function setDescripcion(string $descripcion) {
        return $this->descripcion = $descripcion;
    }

    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

    /**
     * Obtiene todas las categorías.
     */
    public static function getTodas(): ?array {
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("SELECT * FROM categorias");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute();

        return $PDOStatement->fetchAll() ?? null;
    }

    /**
     * Obtiene las categorías activas.
     */
    public function getActivas(): ?array {
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("SELECT * FROM {$this->tabla} WHERE activa = 1");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute();

        return $PDOStatement->fetchAll() ?: null;
    }

    /**
     * Obtiene una categoría por ID.
     * @param int $id
     * @return self|null
     */
    public static function getCategoriaPorId(?int $id = null): ?self {
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("SELECT * FROM categorias WHERE id = :id LIMIT 1");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['id' => $id]);

        $result = $PDOStatement->fetch();

        return $result ?: null;
    }

    /**
     * Inserta una nueva categoria
     * @param string $nombre
     * @param string $descripcion
     * @param bool $activa
     *
     * @return int Devuelve el ID generado
     */
    public static function insert(string $nombre, string $descripcion, bool $activa = true): int{
        $conexion = Conexion::getConexion();

        $sql = "INSERT INTO categorias 
                (nombre, descripcion, activa)
                VALUES (:nombre, :descripcion, :activa)";

        $PDOStatement = $conexion->prepare($sql);
        $PDOStatement->execute([
            'nombre'      => $nombre,
            'descripcion' => $descripcion,
            'activa'      => $activa ? 1 : 0
        ]);
        return (int) $conexion->lastInsertId();
    }

    /**
     * Actualiza una categoría existente.
     *
     * @return bool True si se actualizp
     */
    public function update(): bool {
        $conexion = Conexion::getConexion();

        $sql = "UPDATE categorias 
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    activa = :activa
                WHERE id = :id";

        $PDOStatement = $conexion->prepare($sql);

        return $PDOStatement->execute([
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion,
            'activa'      => $this->activa ? 1 : 0
        ]);
    }

    /**
     * Desactiva una categoria 
     * @return bool si se elimino
    */
    public function delete(): bool {
        $conexion = Conexion::getConexion();
        $PDOStatement = $conexion->prepare("UPDATE categorias SET activa = 0 WHERE id = :id");
        return $PDOStatement->execute(['id' => $this->id]);
    }

    /**
     * Activa un categoria 
     * @return bool si se activo
    */
    public function restore(): bool {
        $conexion = Conexion::getConexion();
        $PDOStatement = $conexion->prepare("UPDATE categorias SET activa = 1 WHERE id = :id");
        return $PDOStatement->execute(['id' => $this->id]);
    }
}