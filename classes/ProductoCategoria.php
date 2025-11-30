<?php

class ProductoCategoria
{
    protected $tabla =  "producto_categoria";

    protected $id;
    protected $producto_id;
    protected $categoria_id;

    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getId(){
        return $this->id;
    }

    public function getProductoId(){
        return $this->producto_id;
    }

    public function getCategoriaId(){
        return $this->categoria_id;
    }

    /* ----------------------------------
    |  Setters
    +---------------------------------- */
    public function id(int $id){
        $this->id = $id;
    }

    public function setProductoId(int $producto_id){
        $this->producto_id = $producto_id;
    }

    public function setCategoriaId(int $categoria_id){
        $this->categoria_id = $categoria_id;
    }


    public static function getCategoriasPorProductoId(int $productoId): ?array
    {
        $conexion = Conexion::getConexion();
        $query = "
            SELECT c.* 
            FROM categorias c
            JOIN producto_categoria pc ON c.id = pc.categoria_id
            WHERE pc.producto_id = :producto_id
        ";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, 'Categorias');
        $PDOStatement->execute([
            'producto_id' => $productoId
        ]);

        $resultado = $PDOStatement->fetchAll() ?: null;
        return $resultado;
    }

    /**
     * Agrega una categoria a un producto
     * @param int $producto_id
     * @param int $categoria_id
     * @return int id del registro insertado
     */
    public static function insert(int $producto_id, int $categoria_id): int{
        $conexion = Conexion::getConexion();
        $query = 'INSERT INTO producto_categoria VALUES (NULL, :producto_id, :categoria_id)';
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute([
            'producto_id' => $producto_id,
            'categoria_id' => $categoria_id
        ]);
        return (int) $conexion->lastInsertId();
    }

    /**
     * Elimina todas las categorias de un producto
     * @param int $producto_id
     * @return bool
     */
    public static function deleteTodosProducto(int $producto_id): bool{
        $conexion = Conexion::getConexion();
        $PDOStatement = $conexion->prepare("DELETE FROM producto_categoria WHERE producto_id = :producto_id");
        $resultado = $PDOStatement->execute(['producto_id' => $producto_id]);
        return (bool) $resultado;
    }
    
}
