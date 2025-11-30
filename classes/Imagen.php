<?php

class Imagen{

    /**
     * Busca la imagen localmente, sino, revisa que sea una url externa
     */
    public static function buscarImagen(?string $imagen, ?string $ubicacion = null): string{
        if($imagen){
            if(file_exists($ubicacion.$imagen)){
                return $ubicacion.$imagen;
            }
            if (filter_var($imagen, FILTER_VALIDATE_URL)) {
                return $imagen;
            }
        }
        return self::imageNotFound();
    }


    /**
     * Retorna una imagen por defecto
     * @return string imagen
     */
    public static function imageNotFound():string{
        return "https://nftcalendar.io/storage/uploads/2022/02/21/image-not-found_0221202211372462137974b6c1a.png";
    }

    /**
     * Subir una imagen
     * @param string $directorio ubicacion de la carpeta
     * @param array $datosArchivo datos del archiv
     * @return string nombre del archivo
     * @throws Exception si no se pudo subir el archivo
     */
    public static function subirImagen(string $directorio, array $datosArchivo): string{
        $archivo = explode('.', $datosArchivo['name']);
        $extension = end($archivo);
        $nombre = time().".$extension";
        $archivoSubido = move_uploaded_file($datosArchivo['tmp_name'], "$directorio/$nombre");

        if(!$archivoSubido){
            throw new Exception("Error, no se puede subir el archivo");
        }
        return $nombre;
    }

    public static function borrarImagen(string $ubicacion){
        if(file_exists($ubicacion)){
            $eliminado = unlink($ubicacion);
            if(!$eliminado){
                throw new Exception("No se pudo eliminar la imagen");
            }
            return true;
        }
        return false;
    }
}

?>