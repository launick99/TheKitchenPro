<?php

/**
 * Clase para manejar los datos técnicos de un producto.
 * Representa registros de la tabla `producto_dato_tecnico`.
 */
class DatosTecnicos {
    protected $tabla = 'producto_dato_tecnico';

    protected $id;
    protected $producto_id;
    protected $clave;
    protected $valor;

    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getId(){
        return $this->id;
    }

    public function getProductoId(){
        return $this->producto_id;
    }

    public function getClave(){
        return $this->clave;
    }

    public function getValor(){
        return $this->valor;
    }

    /* ----------------------------------
    |  Setters
    +---------------------------------- */
    public function setProductoId(int $id){
        $this->producto_id = $id;
    }

    public function setClave(string $clave){
        $this->clave = $clave;
    }

    public function setValor(string $valor){
        $this->valor = $valor;
    }

    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

    /**
     * Obtiene todos los datos técnicos de un producto.
     */
    public function getByProductoId(int $productoId): array {
        $connection = (new Conexion)->getConexion();
        $PDOStatement = $connection->prepare("SELECT * FROM {$this->tabla} WHERE producto_id = :id");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['id' => $productoId]);
        return $PDOStatement->fetchAll();
    }
}
