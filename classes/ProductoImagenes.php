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

    public function getUrl(string $ubicacion = "assets/img/productos/"): string{
        return Imagen::buscarImagen($this->url, $ubicacion.$this->getProductoId()."/");
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
    public static function getByProductoId(int $productoId): ?array {
        $connection = Conexion::getConexion();
        $query = "SELECT * FROM producto_imagenes WHERE producto_id = :id";

        $PDOStatement = $connection->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['id' => $productoId]);

        return $PDOStatement->fetchAll();
    }

    /**
     * Obtiene una imagen por su ID.
     * @param id
     */
    public static function getById(int $id): ?self {
        $connection = Conexion::getConexion();
        $query = "SELECT * FROM producto_imagenes WHERE id = :id LIMIT 1";

        $PDOStatement = $connection->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['id' => $id]);
        return $PDOStatement->fetch() ?: null;
    }

    /**
     * Elimina todas las imagenes de un producto
     * @param int $producto_id
     * @return bool
     */
    public static function deleteTodosProducto(int $producto_id): bool{
        $conexion = Conexion::getConexion();
        $PDOStatement = $conexion->prepare("DELETE FROM producto_imagenes WHERE producto_id = :producto_id");
        $resultado = $PDOStatement->execute(['producto_id' => $producto_id]);
        return (bool) $resultado;
    }

    /**
     * Elimina
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool{
        $conexion = Conexion::getConexion();
        $PDOStatement = $conexion->prepare("DELETE FROM producto_imagenes WHERE id = :id");
        $resultado = $PDOStatement->execute(['id' => $id,]);
        return (bool) $resultado;
    }


    /**
     * Agrega una imagen
     * @param int $producto_id
     * @param string $url la ubicacion del archivo
     * @param bool activo
     * @return bool si se inserto
     */
    public static function insert(int $producto_id, string $url, ?bool $activo = true): bool {
        $connection = Conexion::getConexion();
        $query = "INSERT INTO producto_imagenes VALUES (NULL, :producto_id, :url, :activo)";
        $PDOStatement = $connection->prepare($query);

        return $PDOStatement->execute([
            'producto_id' => $producto_id,
            'url' => $url,
            'activo' => $activo,
        ]);
    }
}
