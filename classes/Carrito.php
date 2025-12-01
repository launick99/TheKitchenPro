<?php

class Carrito{


    /**
     * Devuelve un lindo valor hardcodeado de envio
     */
    public static function getEnvio(): float{
        return 10000.00;
    }

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
     * Actualiza la cantidad de un producto
     * @param int $id del producto
     * @param int $cantidad
     */
    public static function actualizarCantidad(int $productoId, int $cantidad): void {
        $producto = Producto::getProductById($productoId);
        if($cantidad >= $producto->getStock()?->getStock()){
            throw new Exception("Error: no hay suficientes unidades");
            return;
        }
        if(isset($_SESSION['carrito'][$productoId])){
            $_SESSION['carrito'][$productoId]['cantidad'] = $cantidad;
        }
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
        return $total + self::getEnvio();
    }
}

?>