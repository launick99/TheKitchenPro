<?php

class ProductoCategoria
{
    protected $tabla =  "producto_categoria";

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
}
