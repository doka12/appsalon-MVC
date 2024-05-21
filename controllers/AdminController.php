<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {
    public static function index(Router $router) {
         
        if(!isset($_SESSION)) {
           session_start();
        }

        isAdmin();

        $fecha = $_GET['fecha'] ?? $fecha = date('Y-m-d'); // Lee el valor de la fecha pasada or GET o añade la actual del servidor
        $fechas = explode('-',$fecha);       // Separamos la fecha en año,mes y dia
        
        // Comprobamos que fecha es válida
        if(!checkdate($fechas[1],$fechas[2],$fechas[0])) {   // Las posiciones en el array creado
            header('Location: /404');       
        } 
                     
        // Consultar BD

        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        $citas = AdminCita::SQL($consulta);
        
        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],       // Nombre del cliente
            'citas' => $citas,                     // Paso resultado de la consulta a BD a la vista
            'fecha' => $fecha                      // Paso fecha de hoy
        ]);
    }
}