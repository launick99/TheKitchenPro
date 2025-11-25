<?php
class Catalogo {
    /**
     * @var array Lista de productos en el catálogo.
     */
    private array $productos;

    public function __construct() {
        $this->productos = (new Producto())->getTodos();
    }

    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

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

    /**
     * Obtiene una lista de categorías únicas de los productos en el catálogo.
     * @return array Lista de categorías únicas.
     */
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
     * @param array $categoria La categoría o categorías para filtrar.
     * @return array Lista de productos que pertenecen a las categorías especificadas.
     */
    public function filtrarPorCategoria(array $categorias): array {
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
     * @return array Lista de productos que cumplen con el criterio de stock mínimo.
     */
    public function filtrarPorStockMinimo(int $minimo): array {
        return array_filter($this->productos, function($producto) use ($minimo) {
            return $producto->getStock() >= $minimo;
        });
    }
}