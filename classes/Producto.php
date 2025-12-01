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
    public function getDescripcion(): ?string{ 
        return $this->descripcion; 
    }
    public function getPrecio(): ?float{ 
        return $this->precio; 
    }
    public function getFechaIngreso(): ?string{ 
        return $this->fechaIngreso; 
    }

    public function getImagenReal(): ?string{ 
        return$this->imagen; 
    }
    /**
     * Devuelve la url de la imagen del prodycto o una por defecto si no existe
     */
    public function getImagen(?string $url = 'assets/img/productos/'): string{ 
        return Imagen::buscarImagen($this->imagen, $url); 
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
        return ProductoImagenes::getByProductoId($this->id);
    }

    public function getDatosTecnicos(): ?array{
        $datosTecnicos = new DatosTecnicos();
        return DatosTecnicos::getByProductoId($this->id);
    }

    /**
     * Obtiene el stock de un producto
     * @return Stock
     */
    public function getStock(): ?Stock{
        return Stock::getByProductoId($this->id);
    }

    /**
     * Obtiene las categorias a las que esta agregada un prodycto
     * @return array
     */
    public function getCategorias(): ?array{
        return ProductoCategoria::getCategoriasPorProductoId($this->id);
    }

    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

    /**
     * Obtiene todos los productos desde la base de datos.
     * @return array Lista de productos.
     */
    public static function getTodos(): array {
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("SELECT * FROM producto");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute();

        return $PDOStatement->fetchAll();
    }

    /**
     * Obtiene un producto por su ID desde la base de datos.
     * @param int $id El ID del producto.
     * @return Producto|null El producto si se encuentra
     */
    public static function getProductById(int $id): ?self{
        $connection = Conexion::getConexion();
        $PDOStatement = $connection->prepare("SELECT * FROM producto WHERE id = :id LIMIT 1");
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['id' => $id]);

        return $PDOStatement->fetch() ?? null;
    }

    /**
     * Obtiene productos similares
     * @return array
     */
    public function getProductosSimilares(): array{
        $connection = Conexion::getConexion();
        $categorias = $this->getCategorias();
        if (!$categorias || count($categorias) === 0){
            return [];
        }
        
        $categoriasIds = [];
        $in = [];
        foreach ($categorias as $categoria) {
            $categoriasIds[] = $categoria->getId();
            $in[] = '?';
        }
        $in = implode(',', $in);

        $sql = "
            SELECT producto.*, COUNT(*) AS coincidencias
            FROM producto
            INNER JOIN producto_categoria categoria ON producto.id = categoria.producto_id
            WHERE categoria.categoria_id IN ($in)
            AND producto.id <> ?
            AND producto.activo = 1
            GROUP BY producto.id
            ORDER BY coincidencias DESC, producto.nombre ASC
            LIMIT 4
        ";

        $stmt = $connection->prepare($sql);
        
        $params = array_merge($categoriasIds, [$this->id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute($params);

        return $stmt->fetchAll() ?: [];
    }
    
    /**
     * Filtra productos.
     *
     * @param string|null $buscar Palabra clave para buscar en nombre o descripcin
     * @param array|null $categorias IDs de categorías
     * @param array|null $rangoPrecio Array con ['min' => float, 'max' => float].
     * @param bool $soloConStock Si true, solo devuelve productos con stock > 0.
     *
     * @return array Lista de objetos Producto filtrados.
     */
    public function filtrarProductos(?string $buscar = null, ?array $categorias = null, ?array $rangoPrecio = null, bool $soloConStock = false): array {
        $connection = Conexion::getConexion();
        $query = [];
        $parametros = [];

        $sql = "SELECT p.* 
            FROM producto p
            LEFT JOIN producto_categoria pc ON p.id = pc.producto_id
            LEFT JOIN producto_stock s ON p.id = s.producto_id";


        // Filtro por búsqueda
        if ($buscar) {
            $query[] = "(nombre LIKE :buscar OR descripcion LIKE :buscar)";
            $parametros['buscar'] = '%' . $buscar . '%';
        }

        // Filtro por categorías
        if ($categorias && count($categorias) > 0) {
            $categoriaIn = [];
            foreach ($categorias as $categoria) {
                $categoriaIn[] = ':cat_' . $categoria;
                $parametros[':cat_' . $categoria] = $categoria;
            }
            $query[] = "pc.categoria_id IN (". implode(',', $categoriaIn).")";
        }

        // Filtro por rango de precio
        if ($rangoPrecio) {
            if (isset($rangoPrecio[0])) {
                $query[] = "precio >= :minPrecio";
                $parametros['minPrecio'] = (float)$rangoPrecio[0];
            }
            if (isset($rangoPrecio[1])) {
                $query[] = "precio <= :maxPrecio";
                $parametros['maxPrecio'] = (float)$rangoPrecio[1];
            }
        }
        // Filtro por stock
        if ($soloConStock) {
            $query[] = "s.stock > 0";
        }

        $sql .= ' WHERE activo = 1';
        if (count($query) > 0) {
            $sql .= " AND " . implode(" AND ", $query);
        }

        $sql .= " GROUP BY p.id";

        $PDOStatement = $connection->prepare($sql);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute($parametros);

        return $PDOStatement->fetchAll();
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

    /**
     * Agrega un nuevo producto a la base de datos
     * @param string $nombre del producto
     * @param string $descripcion
     * @param float $precio
     * @param string $fechaIngreso
     * @param bool|null $activo = true
     * @param string|null $imagen
     * @return int id del producto insertado
     */
    public static function insert(string $nombre, string $descripcion, float $precio, string $fechaIngreso, ?bool $activo = true, ?string $imagen = null): ?int{
        $conexion = Conexion::getConexion();
        $sql = "INSERT INTO producto VALUES (NULL, :nombre, :descripcion, :precio, :imagen, :fechaIngreso, :activo)";

        $PDOStatement = $conexion->prepare($sql);

        $PDOStatement->execute([
            'nombre'        => $nombre,
            'descripcion'   => $descripcion,
            'precio'        => $precio,
            'fechaIngreso'  => $fechaIngreso,
            'activo'        => $activo ? 1 : 0,
            'imagen'        => $imagen
        ]);

        return (int) $conexion->lastInsertId();
    }

    /**
     * Actualiza un producto
     * @param int $id
     * @param string $nombre
     * @param string $descripcion
     * @param float $precio
     * @param string $fechaIngreso
     * @param bool|null $activo
     * @param string|null $imagen
     * @return bool true si se actualizo
     */
    public static function update(int $id, string $nombre, string $descripcion, float $precio, string $fechaIngreso, ?bool $activo = true, ?string $imagen = null): bool {
        $conexion = Conexion::getConexion();
        $producto = Producto::getProductById($id);

        $sql = "UPDATE producto
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio,
                    imagen = :imagen,
                    fechaIngreso = :fechaIngreso,
                    activo = :activo
                WHERE id = :id";

        $PDOStatement = $conexion->prepare($sql);

        $PDOStatement->execute([
            'id'            => $id,
            'nombre'        => $nombre,
            'descripcion'   => $descripcion,
            'precio'        => $precio,
            'fechaIngreso'  => $fechaIngreso,
            'activo'        => $activo ? 1 : 0,
            'imagen'        => $imagen ?? $producto->getImagen()
        ]);

        return $PDOStatement->rowCount() > 0;
    }

    /**
     * Desactiva un producto 
     * @return bool si se elimino
    */
    public function delete(): bool {
        $conexion = Conexion::getConexion();

        // $PDOStatement = $conexion->prepare("DELETE FROM producto WHERE id = :id");
        // soy mas de dar de baja
        $PDOStatement = $conexion->prepare("UPDATE producto SET activo = 0 WHERE id = :id");
        return $PDOStatement->execute(['id' => $this->id]);
    }

    /**
     * Activa un producto 
     * @return bool si se activo
    */
    public function restore(): bool {
        $conexion = Conexion::getConexion();
        $PDOStatement = $conexion->prepare("UPDATE producto SET activo = 1 WHERE id = :id");
        return $PDOStatement->execute(['id' => $this->id]);
    }
    
}