<?php

/**
 * Clase para manejar la conección a la DB
 * @method getConnection Obtener conexión a la base de datos
 */
class Conexion{
    protected const DB_HOST = DB_HOST;
    protected const DB_NAME = DB_NAME;
    protected const DB_USER = DB_USER;
    protected const DB_PASS = DB_PASS;

    private const DB_DSN = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=utf8';

    private PDO $db;

    /**
     * Obtener conexión a la base de datos
     * @return PDO
     * @throws Exception Si no se puede conectar a la base de datos
     */
    public function getConnection(): PDO{
        try {
            $this->db = new PDO(self::DB_DSN, self::DB_USER, self::DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Throwable $th) {
            // die('Error al conectar con Mysql: ' . $th->getMessage());
            throw new Exception('Error al conectar con Mysql: ' . $th->getMessage(), 500);
            
        }
        return $this->db;
    }
}