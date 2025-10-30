<?php

/**
 * Funcion Autoload para cargar clases automaticamente
 * @param string $className Nombre de la clase a cargar
 * @return void
 */
function autoloadClasses(string $className): void{
    $className = str_replace('\\', '/', $className);
    $filePath = __DIR__ . '/../classes/' . $className . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        throw new Exception("Clase no encontrada: " . $filePath);
    }

}

// Registrar la funcion de autoload
spl_autoload_register('autoloadClasses');