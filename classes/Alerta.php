<?php

class Alerta{
    
    /**
     * Registra una alerta
     * @param string $tipo
     * @param string $mensaje
     */
    public static function agregarAlerta(string $tipo, string $mensaje): void{
        $_SESSION['alertas'][] = [
            'tipo' => $tipo,
            'mensaje' => $mensaje,
        ];
    }

    /**
     * Vacia la lista de alertas
     */
    public static function limpiarAlertas(): void{
        $_SESSION['alertas'] = [];
    }

    /**
     * Devuelve todas las alertas
     * @return string|null
     */
    public static function getAlertas(): ?string{
        if(!empty($_SESSION['alertas'])){
            $alertasActuales = "";
            foreach ($_SESSION['alertas'] as $alerta) {
                $alertasActuales .= self::printAlerta($alerta);
            }
            self::limpiarAlertas();
            return $alertasActuales;
        }
        return null;
    }

    private static function printAlerta($alerta): string{
        return "
            <div class='alert alert-{$alerta['tipo']} alert-dismissible fade show' role='alert'>
                {$alerta['mensaje']}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                </button>
            </div>
        ";
    }
}   