<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {
    // Vemos servicios que se ofrecen
    public static function index() {
        $servicios = Servicio::all();       // Trae un array con todos los datos de la bd ya sanitizados,etc
        echo json_encode($servicios);       // Formatea a formato json
    }
    // Guardar citas en BD
    public static function guardar() {
        // Almacena la cita y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        // Devuelve id de la cita
        $id = $resultado['id'];

        // Almacena la cita y el servicio
        $idServicios = explode(',', $_POST['servicios']); // Array con los valores separados por comas

        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            // Objeto con los datos a guardar
            $citaServicio = new CitaServicio($args);
            // Guardo en BD
            $citaServicio->guardar();
        }

        // Devolvemos una respuesta
        $respuesta = [
            'resultado'=> $resultado,
        ];
        echo json_encode($respuesta);
    }
    // Eliminar
    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];                 // Asigno id de la cita a eliminar
            $cita = Cita::find($id);            // Asignamos cita de la BD con ese Id
            $cita->eliminar();                  // Elimino de BD
            header('Location: '. $_SERVER['HTTP_REFERER']); // Página que estamos
        }
    }
}


