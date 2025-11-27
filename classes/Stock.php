<?php

/**
 * Clase que representa el stock de un producto.
 */
class Stock {
    protected $tabla = 'producto_stock';

    protected $id;
    protected $producto_id;
    protected $stock;
    protected $stock_minimo;
    protected $actualizado;

    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getId(): int{
        return $this->id;
    }

    public function getProductoId():int{
        return $this->producto_id;
    }

    public function getStock(): int{
        return $this->stock;
    }

    public function getStockMinimo(): int{
        return $this->stock_minimo;
    }

    public function getFechaActualizacion(): string{
        return $this->actualizado;
    }

    /* ----------------------------------
    |  Setters
    +---------------------------------- */
    public function setProductoId(int $id){
        $this->producto_id = $id;
    }

    public function setStock(int $stock){
        if ($stock < 0) {
            throw new Exception("El stock no puede ser negativo");
        }
        $this->stock = $stock;
    }

    public function setStockMinimo(int $min){
        $this->stock_minimo = $min;
    }

    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

    /**
     * Obtiene el registro de stock asociado a un producto.
     * @param int $id ID del producto.
     * @return ?self El objeto Stock o null si no se encuentra.
     */
    public static function getByProductoId(int $id): ?self {
        $connection = Conexion::getConexion();
        $query = "SELECT * FROM producto_stock WHERE producto_id = :id";
        $PDOStatement = $connection->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['id' => $id]);

        return $PDOStatement->fetch() ?? null;
    }

    /**
     * Verifica si el producto tiene stock suficiente.
     * @return bool True si hay stock, false si no lo hay.
     */
    public function tieneStock(): bool {
        return $this->stock > 0;
    }

    /**
     * Indica si el producto está bajo el stock mínimo.
     * @return bool True si el stock es menor o igual al mínimo, false en caso contrario.
     */
    public function stockBajo(): bool {
        return $this->stock <= $this->stock_minimo;
    }
}
