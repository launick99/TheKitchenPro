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
    protected $fechaIngreso;
    protected $activo;
    protected $imagen;

    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getId(): int{ 
        return $this->id; 
    }
    public function getNombre(): string{ 
        return $this->nombre; 
    }
    public function getDescripcion(): string{ 
        return $this->descripcion; 
    }
    public function getPrecio(): float{ 
        return $this->precio; 
    }
    public function getFechaIngreso(): string{ 
        return $this->fechaIngreso; 
    }
    /**
     * Devuelve la url de la imagen del prodycto o una por defecto si no existe
     */
    public function getImagen(): string{ 
        return $this->imagen ?? 'https://nftcalendar.io/storage/uploads/2022/02/21/image-not-found_0221202211372462137974b6c1a.png'; 
    }
    public function getActivo(): bool{
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
    public function setFechaIngreso(string $fecha){
        $this->fechaIngreso = new DateTime($fecha);
    }
    public function setImagen(string $imagen){ 
        return $this->imagen = $imagen; 
    }

    /* ----------------------------------
    |  Relaciones
    +---------------------------------- */

    /**
     * Obtiene todas las imágenes asociadas al producto.
     * @return array Lista de URLs de imágenes.
     */
    public function getImagenes(): ?array {
        $productoImagenes = new ProductoImagenes();
        return $productoImagenes->getByProductoId($this->id);
    }

    public function getDatosTecnicos(): ?array{
        $datosTecnicos = new DatosTecnicos();
        return $datosTecnicos->getByProductoId($this->id);
    }

    public function getStock(): ?Stock{
        $stock = new Stock();
        return $stock->getByProductoId($this->id);
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
        $stock = $this->getStock();
        if(!$stock){
            return false;
        }
        return $stock->tieneStock();
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