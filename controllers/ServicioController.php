<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController
{

    public static function index(Router $router)
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        isAdmin();                             // Protegemos las rutas

        $servicios = Servicio::all();          // Trae todos los servicios de la BD

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router)
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        isAdmin();
        // Crear variable de servicio
        $servicio = new Servicio;           // instancia vacía que evita undefined y usamos para leer datos y pasarlos a la vista
        // Alertas
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router)
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        isAdmin();

        if (!is_numeric($_GET['id'])) {      // Validamos que sea número
            return;
        }

        $servicio = Servicio::find($_GET['id']);    // Así vemos los valores que ya tiene en los input desde la BD
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(Router $router)
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        isAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }

        $router->render('servicios/eliminar', [
            'nombre' => $_SESSION['nombre']
        ]);
    }
}
