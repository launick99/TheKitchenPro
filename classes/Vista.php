<?php

class Vista
{
    private $seccion;
    private $rutas = [
        'inicio' =>     ['view' => 'views/home.php',                'title' => 'Inicio'     ],
        'catalogo' =>   ['view' => 'views/catalogo.php',            'title' => 'Catalogo'   ],
        'detalle' =>    ['view' => 'views/producto_detalle.php',    'title' => 'Producto'   ],
        'contacto' =>   ['view' => 'views/contacto/contacto.php',   'title' => 'Contacto'   ],
        'alumno' =>     ['view' => 'views/alumno/alumno.php',       'title' => 'Alumno'     ],
        'gracias' =>    ['view' => 'views/contacto/gracias.php',    'title' => 'Gracias!'   ],
    ];
    private $view;
    private $title;

    public function __construct($seccion)
    {
        $this->seccion = $seccion;
        $this->validarVista();
    }
    
    /* ----------------------------------
    |  Getters
    +---------------------------------- */
    public function getSeccion()
    {
        return $this->seccion;
    }
    public function getRutas()
    {
        return $this->rutas;
    }
    public function getView()
    {
        return $this->view;
    }
    public function getTitle()
    {
        return $this->title;
    }

    /* ----------------------------------
    |  Setters
    +---------------------------------- */
    public function setSeccion($seccion)
    {
        $this->seccion = $seccion;
        $this->validarVista();
    }
    public function setRutas(array $rutas)
    {
        $this->rutas = $rutas;
        $this->validarVista();
    }
    public function setView($view)
    {
        $this->view = $view;
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /* ----------------------------------
    |  Métodos
    +---------------------------------- */

    /**
     * Valida la sección solicitada y establece la vista y el título correspondientes.
     * Si la sección no es válida, establece una vista de error 404.
     */
    private function validarVista(): void
    {

        if (array_key_exists($this->seccion, $this->rutas)) {
            $this->view = $this->rutas[$this->seccion]['view'];
            $this->title = $this->rutas[$this->seccion]['title'];
        } else {
            $this->view = 'views/errors/404.php';
            $this->title = 'Página no encontrada';
        }
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