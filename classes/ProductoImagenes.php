<?php

/**
 * Clase que representa las imágenes asociadas a un producto.
 */
class ProductoImagenes {

    protected $tabla = 'producto_imagenes';

    protected $id;
    protected $producto_id;
    protected $url;
    protected $activo;

    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getId(): int{
        return $this->id;
    }

    public function getProductoId(): int{
        return $this->producto_id;
    }

    public function getUrl(): string{
        return $this->url;
    }

    public function getActivo(): bool{
        return $this->activo;
    }

    /* ----------------------------------
    |  Setters
    +---------------------------------- */
    public function setProductoId(int $producto_id){
        $this->producto_id = $producto_id;
    }

    public function setUrl(string $url){
        $this->url = $url;
    }

    public function setActivo(bool $activo){
        $this->activo = $activo;
    }

    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

    /**
     * Obtiene todas las imágenes activas de un producto.
     * @param int $productoId
     * @return self|null Lista de imágenes o null
     */
    public function getByProductoId(int $productoId): ?array {
        $connection = Conexion::getConexion();
        $query = "SELECT * FROM {$this->tabla} WHERE producto_id = :id";

        $PDOStatement = $connection->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['id' => $productoId]);

        return $PDOStatement->fetchAll();
    }

    /**
     * Obtiene una imagen por su ID.
     */
    public function getById(int $id): ?self {
        $connection = Conexion::getConexion();
        $query = "SELECT * FROM {$this->tabla} WHERE id = :id";
        $PDOStatement = $connection->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['id' => $id]);
        return $PDOStatement->fetch() ?: null;
    }

    /**
     * Inserta un nuevo registro de imagen.
     */
    public function crear(): bool {
        $connection = Conexion::getConexion();
        $query = "INSERT INTO {$this->tabla} (producto_id, url, activo)
                  VALUES (:producto_id, :url, :activo)";
        $PDOStatement = $connection->prepare($query);

        return $PDOStatement->execute([
            'producto_id' => $this->producto_id,
            'url' => $this->url,
            'activo' => $this->activo ?? 1,
        ]);
    }

    /**
     * Actualiza la imagen existente.
     */
    public function actualizar(): bool {
        $connection = Conexion::getConexion();
        $query = "UPDATE {$this->tabla}
                  SET url = :url, activo = :activo
                  WHERE id = :id";

        $PDOStatement = $connection->prepare($query);

        return $PDOStatement->execute([
            'url' => $this->url,
            'activo' => $this->activo,
            'id' => $this->id,
        ]);
    }

    /**
     * Marca una imagen como inactiva.
     */
    public function desactivar(): bool {
        $this->activo = 0;

        $connection = Conexion::getConexion();
        $query = "UPDATE {$this->tabla} SET activo = 0 WHERE id = :id";

        $PDOStatement = $connection->prepare($query);

        return $PDOStatement->execute(['id' => $this->id]);
    }

    /**
     * Borra una imagen de la base de datos.
     */
    public function eliminar(): bool {
        $connection = Conexion::getConexion();
        $query = "DELETE FROM {$this->tabla} WHERE id = :id";

        $PDOStatement = $connection->prepare($query);

        return $PDOStatement->execute(['id' => $this->id]);
    }
}
