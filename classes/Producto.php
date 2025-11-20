<?php

/**
 * Clase que representa un producto en el catálogo.
 */
class Producto {
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $stock;
    private $categoria;
    private $fechaIngreso;
    private $data;
    private $imagen;

    public function __construct($data) {
        $this->setId($data['id']);
        $this->setNombre($data['nombre']);
        $this->setDescripcion($data['descripcion']);
        $this->setPrecio($data['precio']);
        $this->setStock($data['stock']);
        $this->setCategoria($data['categoria']);
        $this->setImagen($data['imagen'] ? './assets/img/productos' . $data['imagen'] : '');
        $this->setFechaIngreso($data['fechaIngreso']);
        $this->setData($data['datos_tecnicos'] ?? []);
    }

    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getId(){ 
        return $this->id; 
    }
    public function getNombre(){ 
        return $this->nombre; 
    }
    public function getDescripcion(){ 
        return $this->descripcion; 
    }
    public function getPrecio(){ 
        return $this->precio; 
    }
    public function getStock(){ 
        return $this->stock; 
    }
    public function getCategoria(){ 
        return $this->categoria; 
    }
    public function getFechaIngreso(){ 
        return $this->fechaIngreso; 
    }
    public function getImagen(){ 
        return $this->imagen; 
    }

    public function getData(){
        return $this->data ?? [];
    }

    public function getImagenes() {
        return $this->getData()['imagenes'] ?? [];
    }

    /* ----------------------------------
    |  Setters
    +---------------------------------- */

    public function setId($id){ 
        $this->id = $id; 
    }
    public function setNombre($nombre){ 
        $this->nombre = $nombre; 
    }
    public function setDescripcion($descripcion){ 
        $this->descripcion = $descripcion; 
    }
    public function setPrecio($precio){
        if (!is_numeric($precio) || $precio < 0) {
            throw new Exception("El precio no es válido");
        }
        $this->precio = $precio;
    }
    public function setStock($stock){
        $this->stock = $stock;
    }
    public function setCategoria($categoria){
        $this->categoria = $categoria;
    }
    public function setFechaIngreso($fecha){
        $this->fechaIngreso = new DateTime($fecha);
    }
    public function setImagen($imagen){ 
        return $this->imagen = $imagen; 
    }
    public function setData($data){
        $this->data = $data;
    }   


    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

    /**
     * Formatea el precio del producto en formato monetario.
     * @return string El precio formateado.
     */
    public function formatearPrecio(): string{
        return '$'.number_format($this->precio, 2, ',', '.');
    }

    /**
     * Verifica si el producto está disponible en stock.
     * @return bool True si el stock es mayor a 0, false en caso contrario
     */
    public function estaDisponible(): bool{
        return $this->stock > 0;
    }

    /**
     * Obtiene la fecha de ingreso del producto en formato 'd/m/Y'.
     * @return string La fecha formateada.
     */
    public function obtenerFechaFormateada(): string {
        return $this->fechaIngreso->format('d/m/Y');
    }

    /**
     * Obtiene una versión corta de la descripción del producto.
     * Si la descripción tiene más de 100 caracteres, se trunca y se añade '...'.
     * @return string La descripción corta del producto.
     */
    public function getDescripcionCorta(): string{
        if(strlen($this->descripcion) >= 100) {
            return substr($this->descripcion, 0, 100) . '...';
        }
        return $this->descripcion;
    }
}