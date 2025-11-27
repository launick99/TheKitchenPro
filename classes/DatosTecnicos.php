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
        $conexion = Conexion::getConexion();
        $PDOStatement = $conexion->prepare("SELECT * FROM {$this->tabla} WHERE producto_id = :id");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['id' => $productoId]);
        return $PDOStatement->fetchAll();
    }

    /**
     * Elimina un dato tecnico
     * @param int $id
     * @return bool 
     */
    public static function delete(int $id): bool{
        $conexion = Conexion::getConexion();
        $PDOStatement = $conexion->prepare("DELETE FROM producto_dato_tecnico WHERE id = :id");
        $resultado = $PDOStatement->execute(['id' => $id]);
        return (bool) $resultado;
    }

    /**
     * Elimina todos los datos tecnicos de un producto
     * @param int $producto_id
     * @return bool
     */
    public static function deleteTodosProducto(int $producto_id): bool{
        $conexion = Conexion::getConexion();
        $PDOStatement = $conexion->prepare("DELETE FROM producto_dato_tecnico WHERE producto_id = :producto_id");
        $resultado = $PDOStatement->execute(['producto_id' => $producto_id]);
        return (bool) $resultado;
    }


    /**
     * Añade un dato tecnico
     * @param int $id_producto
     * @param string clave nombre del dato
     * @param string valor del dato
     * @return int id del resultado
     */
    public static function insert(int $id_producto, string $clave, string $valor): int{
        $conexion = Conexion::getConexion();
        $PDOStatement = $conexion->prepare("INSERT INTO producto_dato_tecnico VALUES (NULL, :id_producto, :clave, :valor)");
        $PDOStatement->execute(['id_producto' => $id_producto, 'clave' => $clave, 'valor' => $valor]);
        return (int) $conexion->lastInsertId();
    }
}
