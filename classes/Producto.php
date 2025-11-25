<?php

/**
 * Clase que representa un producto en el catálogo.
 */
class Producto {
    protected $tabla = 'producto';

    protected $id;
    protected $nombre;
    protected $descripcion;
    protected $precio;
    protected $stock;
    protected $fechaIngreso;
    protected $activo;
    protected $data;
    protected $imagen;

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
    public function getFechaIngreso(){ 
        return $this->fechaIngreso; 
    }
    public function getImagen(){ 
        return 'assets/img/productos/'.$this->imagen; 
    }
    public function getActivo(){
        return $this->activo;
    }

    /* ----------------------------------
    |  Setters
    +---------------------------------- */

    public function setId(int $id){ 
        $this->id = $id; 
    }
    public function setNombre(string $nombre){ 
        $this->nombre = $nombre; 
    }
    public function setDescripcion(string $descripcion){ 
        $this->descripcion = $descripcion; 
    }
    public function setPrecio(float $precio){
        if (!is_numeric($precio) || $precio < 0) {
            throw new Exception("El precio no es válido");
        }
        $this->precio = $precio;
    }
    public function setStock(int $stock){
        $this->stock = $stock;
    }
    public function setFechaIngreso(string $fecha){
        $this->fechaIngreso = new DateTime($fecha);
    }
    public function setImagen(string $imagen){ 
        return $this->imagen = $imagen; 
    }
    public function setData(string $data){
        $this->data = $data;
    }   

    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

    public function getCategoria(){
        return "";
    }

    /**
     * Obtiene todos los productos desde la base de datos.
     * @return array Lista de productos.
     */
    public function getTodos(): array {
        $connection = (new Conexion)->getConexion();
        $PDOStatement = $connection->prepare("SELECT * FROM {$this->tabla}");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute();

        return $PDOStatement->fetchAll();
    }

    /**
     * Obtiene un producto por su ID desde la base de datos.
     * @param int $id El ID del producto.
     * @return Producto|null El producto si se encuentra
     */
    public function getProductById(int $id): ?self{
        $connection = (new Conexion)->getConexion();
        $PDOStatement = $connection->prepare("SELECT * FROM {$this->tabla} WHERE id = :id");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['id' => $id]);

        return $PDOStatement->fetch() ?? null;
    }

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