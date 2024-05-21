<?php

namespace Controllers;

use MVC\Router;

class CitaController {
    public static function index (Router $router) {
        // Iniciamos sesión para acceder a los datos que se introdujeron
        
        // Si no hay sesión iniciada la iniciamos
        if(!isset($_SESSION)){
            session_start();                         // ahora podemos acceder al nombre,etc
        };
        
        isAuth();                                // Si no autenticado envía a login

        $router->render('cita/index',[
            'nombre'=>$_SESSION['nombre'],       // Le pasamos el valor de nombre a la vista
            'id'=>$_SESSION['id']                // Hacemos disponible el id en la vista
        ]);
    }
}