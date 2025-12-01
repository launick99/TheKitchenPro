<?php

class Carrito{



    /**
     * devuelve los items del carrito
     * @return array
     */
    public static function listar(): array{
        if(!empty($_SESSION['carrito'])){
            return $_SESSION['carrito'];
        }
        return [];
    }

    /**
     * 
     */
    public static function agregar(int $productoId, int $cantidad){
        $producto = Producto::getProductById($productoId);
        if($producto){
            $_SESSION['carrito'][$productoId] = ['producto' => $producto, 'cantidad' => $cantidad];
        }
    }

    /**
     * Elimina un producto del carrito
     * @param int $productoId
     */
    public static function remover(int $productoId){
        if(isset($_SESSION['carrito'][$productoId])){
            unset($_SESSION['carrito'][$productoId]);
        }
    }

    /**
     * Limpia el carrito
     */
    public static function limpiar(){
        $_SESSION['carrito'] = [];
    }

    /**
     * 
     */
    public static function modificar(){

    }

    /**
     * Devuelve el precio total del carrito
     * @return float $precio
     */
    public static function precioFinal(): float{
        $total = 0;
        if(!empty($_SESSION['carrito'])){
            foreach ($_SESSION['carrito'] as $item) {
                $total += $item['producto']?->getPrecio() * $item['cantidad'];
            }
        }
        return $total;
    }
}

?>