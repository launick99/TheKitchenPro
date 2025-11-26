<?php

/**
 * Clase que representa las categorías de productos.
 */
class Categorias {

    protected $tabla = 'categorias';

    protected $id;
    protected $nombre;
    protected $activa;

    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
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

    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

    /**
     * Obtiene todas las categorías.
     */
    public function getTodas(): ?array {
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("SELECT * FROM {$this->tabla}");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute();

        return $PDOStatement->fetchAll() ?: null;
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
     * Marca la categoría como inactiva.
     */
    public function desactivar(): bool {
        $this->activa = 0;
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("UPDATE {$this->tabla} SET activa = 0 WHERE id = :id");
        return $PDOStatement->execute(['id' => $this->id]);
    }

    /**
     * Borra la categoría de la base de datos.
     */
    public function eliminar(): bool {
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("DELETE FROM {$this->tabla} WHERE id = :id");
        return $PDOStatement->execute(['id' => $this->id]);
    }
}