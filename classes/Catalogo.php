<?php
class Catalogo {
    /**
     * @var array Lista de productos en el catálogo.
     */
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

    /**
     * Normaliza un texto convirtiéndolo a minúsculas,
     * reemplazando acentos y espacios por guiones bajos.
     * @param string $texto El texto a normalizar.
     * @return string El texto normalizado.
     */
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

    /**
     * Filtra los productos por una o más categorías.
     * @param array|string $categoria La categoría o categorías para filtrar.
     * @return array Lista de productos que pertenecen a las categorías especificadas.
     */
    public function filtrarPorCategoria(array|string $categoria): array {
        // TODO: Recibir solamente un array
        $categorias = is_array($categoria) ? $categoria : [$categoria];
        $producto = $this->productos;
        $productos = array_filter($producto, function($producto) use ($categorias) {
            $categoria = $this->normalizarTexto($producto->getCategoria());
            return in_array($categoria, $categorias);
        });
        return $productos;
    }

    /**
     * Filtra los productos que tienen un stock mayor o igual al mínimo especificado.
     * @param int $minimo El stock mínimo.
     */
    public function filtrarPorStockMinimo(int $minimo): array {
        return array_filter($this->productos, function($producto) use ($minimo) {
            return $producto->getStock() >= $minimo;
        });
    }
}