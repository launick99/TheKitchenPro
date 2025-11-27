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
    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function getActiva(): bool {
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

        return $result;
    }

    /**
     * Inserta una nueva categoría.
     */
    public function crear(): bool {
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("INSERT INTO {$this->tabla} (nombre, activa) VALUES (:nombre, :activa)");

        return $PDOStatement->execute([
            'nombre' => $this->nombre,
            'activa' => $this->activa ?? 1
        ]);
    }

    /**
     * Actualiza la categoría existente.
     */
    public function actualizar(): bool {
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("UPDATE {$this->tabla} SET nombre = :nombre, activa = :activa WHERE id = :id");

        return $PDOStatement->execute([
            'nombre' => $this->nombre,
            'activa' => $this->activa,
            'id' => $this->id
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