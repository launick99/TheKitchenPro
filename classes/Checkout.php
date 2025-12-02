<?php

class Checkout{
        /**
         * Inserta los datos de la compra en la base
         * @param array $datosCompra datos de la compra
         * @param array $items Array con los productos incluidos
         */
        public static function insert(array $datosCompra, array $items){
            $conexion = Conexion::getConexion();
            $query = "INSERT INTO compra VALUES (NULL, :usuario_id, :importe, :importe_envio, :fecha)";
            $PDOStatement = $conexion->prepare($query);
            $PDOStatement->execute([
                "usuario_id" => $datosCompra['usuario_id'],
                "importe" => $datosCompra['importe'],
                "importe_envio" => $datosCompra['importe_envio'],
                "fecha" => $datosCompra['fecha'],
            ]);

            $id = $conexion->lastInsertId();

            foreach ($items as $item) {
                $producto = $item['producto'];
                $cantidad = $item['cantidad'];

                $query = "INSERT INTO compra_item VALUES (NULL, :compra_id, :producto_id, :cantidad, :importe_item)";
                $PDOStatement = $conexion->prepare($query);
                $PDOStatement->execute([
                    "compra_id" =>      $id,
                    "producto_id" =>    $producto->getId(),
                    "cantidad" =>       $cantidad,
                    "importe_item" =>   $producto->getPrecio(),
                ]);

                $stock = $producto->getStock();
                Stock::update($producto->getId(), $stock?->getStock() - $cantidad, $stock?->getStockMinimo());
            }
        }
}

?>