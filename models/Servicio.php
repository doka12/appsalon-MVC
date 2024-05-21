<?php

namespace Model;

class Servicio extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id','nombre','precio'];        // Crea así un objeto igual que tabla de base de datos

    // Registro atributos
    public $id;
    public $nombre;
    public $precio;

    public function __construct() {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if(!$this->precio) {
            self::$alertas['error'][] = 'El precio es obligatorio';
        }

        if(!is_numeric($this->precio)) {
            self::$alertas['error'][] = 'Sólo se aceptan números en el precio';
        }
        // Si pasa validación
        return self::$alertas;

    }
}