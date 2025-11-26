<?php

/**
 * Clase que se ocupa de manejar las vistas
 * @method void VistaExiste - verifica que la vista exista
 * @method Vista vistaNoAutorizada - devuelve la pantalla 403
 * @method Vista validarVista - valida que la vista este activa y exista
 * @method bool isActive - valida si el nombre de la vista dado es igual al de la vista 
 */
class Vista{

    protected $tabla = 'vistas';

    protected $id;
    protected $nombre;
    protected $titulo;
    protected $ubicacion;
    protected $activa;
    protected $restringido;

    /**
     * @var string Ubicación base de las vistas
     */
    const UBICACION = 'views/';

    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getId(): int{
        return $this->id;
    }
    public function getNombre(): string{
        return $this->nombre;
    }
    /**
     * Obtiene la ubicacion completa de la vista
     */
    public function getUbicacion(): string{
        return self::UBICACION.$this->ubicacion;
    }
    public function getTitulo(): string{
        return $this->titulo;
    }
    public function getRestringido(): bool{
        return $this->restringido;
    }
    public function getActiva(): bool{
        return $this->activa;
    }

    /* ----------------------------------
    |  Setters
    +---------------------------------- */
    public function setId(int $id): void{
        $this->id = $id;
    }
    public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
    }
    /**
     * Establece la ubicación de la vista
     * @param string $ubicacion
     * @throws Exception Si el archivo de la vista no existe
     */
    public function setUbicacion(string $ubicacion): void{
        if(!$this->vistaExiste($ubicacion)){
            throw new Exception("La vista no existe: " . $ubicacion);
        }
        $this->ubicacion = $ubicacion;
    }
    public function setTitulo(string $titulo): void{
        $this->titulo = $titulo;
    }
    public function SetRestringido(bool $restringido): void{
        $this->restringido = $restringido;
    }
    public function SetActiva(bool $activa): void{
        $this->activa = $activa;
    }

    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

    /**
     * Verifica que exista el archivo de la ruta
     * @param string $ubicacion
     * @return bool
     */
    private function vistaExiste(string $ubicacion): bool{
        return file_exists(Self::UBICACION.$ubicacion);
    }

    /**
     * Vista por defecto cuando no se encuentra la sección
     * @return self 404
     */
    private function vistaNoEncontrada(): self{
        $vista = new self();
        $this->setUbicacion('error/404.php');
        $this->setTitulo("404 - Página no encontrada");
        $this->SetRestringido(false);
        $this->SetActiva(true);
        return $this;
    }

    /**
     * Vista por defecto cuando no se encuentra la sección
     * @return self 404
     */
    private function vistaNoDispobible(): self{
        $vista = new self();
        $vista->setUbicacion('error/403.php');
        $vista->setTitulo("403 - Página no disponible :(");
        $vista->SetRestringido(false);
        $vista->SetActiva(true);
        return $vista;
    }

    /**
     * Vista no autorizada
     * @return self 403
     */
    public function vistaNoAutorizada(): self{
        $vista = new self();
        $vista->setUbicacion('error/403.php');
        $vista->setTitulo("403 - Página no accesible");
        $vista->SetRestringido(true);
        $vista->SetActiva(true);
        return $vista;
    }


    /**
     * Valida la sección solicitada y establece la vista y el título correspondientes.
     * Si la sección no es válida, establece una vista de error 404.
     * @param string $nombre nombre de la vista
     * @return self 
     */
    public function validarVista(string $nombre): self{
        $conexion = (new Conexion())->getConexion();
        $query = "SELECT * FROM {$this->tabla} WHERE nombre = :nombre LIMIT 1";
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute([ 'nombre' => $nombre]);

        $vista = $PDOStatement->fetch();

        if($vista){
            if(!$vista->getActiva()){
                return $this->vistaNoDispobible();
            }
            return $vista;
        }

        return $this->vistaNoEncontrada();
    }


    /**
     * Verifica si la sección actual coincide con la sección objetivo.
     * @param string $actual La sección actual.
     * @param string $target La sección objetivo.
     * @return bool True si coinciden, false en caso contrario.
     */
    public static function isActive($actual, $target): bool
    {
        return $actual == $target;
    }
}