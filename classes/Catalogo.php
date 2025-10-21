<?php

require_once 'Producto.php';

class Catalogo {
    private array $productos;

    public function __construct(string $rutaJSON) {
        $this->productos = [];

        if (file_exists($rutaJSON)) {
            $json = file_get_contents($rutaJSON);
            $datos = json_decode($json, true);

            if (is_array($datos)) {
                foreach ($datos as $productoData) {
                    $producto = new Producto($productoData);
                    $this->productos[] = $producto;
                }
            }
        }
    }

    private function normalizarTexto(string $texto): string {
        $texto = strtolower($texto);
        $acentos = ['á', 'é', 'í', 'ó', 'ú', 'ñ'];
        $sinAcentos = ['a', 'e', 'i', 'o', 'u', 'n'];
        $texto = str_replace($acentos, $sinAcentos, $texto);
    
        $texto = str_replace(' ', '_', $texto);
    
        return $texto;
    }

    public function getTodos(): array {
        return $this->productos;
    }

    public function getById(int $id): ?Producto {
        foreach ($this->productos as $producto) {
            if ($producto->getId() === $id) {
                return $producto;
            }
        }
        return null;
    }

    public function getCategorias(): Array {
        $categorias = [];
    
        foreach ($this->productos as $producto) {
            $categoria = $producto->getCategoria();
            if (!in_array($this->normalizarTexto($categoria), $categorias)) {
                $categorias[] = $this->normalizarTexto($categoria);
            }
        }
        sort($categorias);
        return $categorias ?? [];
    }

    public function filtrarPorCategoria(array|string $categoria): array {
        $categorias = is_array($categoria) ? $categoria : [$categoria];
        $producto = $this->productos;
        // var_dump($categorias);
        $productos = array_filter($producto, function($producto) use ($categorias) {
            $categoria = $this->normalizarTexto($producto->getCategoria());
            // var_dump($categoria);
            return in_array($categoria, $categorias);
        });
        // var_dump($productos);
        return $productos;
    }

    public function filtrarPorStockMinimo(int $minimo): array {
        return array_filter($this->productos, function($producto) use ($minimo) {
            return $producto->getStock() >= $minimo;
        });
    }
}