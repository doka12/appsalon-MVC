<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    // Abrir sesion
    public static function login(Router $router) {
        $alertas = [];
        // Si envío es POST
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);                // Leo lo que venga por POST en el objeto
            $alertas = $auth->validarLogin();
            // Validacion de usuario pasada
            if(empty($alertas)) {
                // Comprobar que existe el usuario
                $usuario = Usuario::where('email',$auth->email);
                if($usuario) {
                    // Verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        
                        if(!isset($_SESSION)) {
                            session_start(); // Iniciamos sesión y así tenemos acceso a las vars de sesión
                        }      

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionar
                        if($usuario->admin === "1") {
                            // Es administrador
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');

                        } else {
                            // Es cliente
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error','Usuario no encontrado');
                }
            }
        }
        $router->render('auth/login',[
            'alertas'=> $alertas
        ]);                  // Ruta de la vista a mostrar
    }
    // Cerrar sesion
    public static function logout() {
        // compruebo si ya hay sesión y la abro si no
        if(!isset($_SESSION)) {
            session_start(); 
        }
        // Limpio los datos de sesión
        $_SESSION = [];
        // Redirecciono a login
        header('Location: /');   
    }
    // Crear cuenta
    public static function crear(Router $router) {

        $usuario = new Usuario;                         // Nueva instancia ,con valores por defecto (vacíos)
        
        // Alertas datos vacíos,inicializamos vacío
        $alertas = [];
        // Vemos si es POST
        if($_SERVER['REQUEST_METHOD'] ==='POST') {
        
        // Asignamos datos del formulario al objeto
            $usuario->sincronizar($_POST);              // Mantiene valores aún recargando la página
            $alertas = $usuario->validarNuevaCuenta();  // Vemos si están todos los datos necesarios o no

            // Revisar que alertas está vacío , significa que pasó la validación
            if(empty($alertas)) {
                // Verificar que el usuario no está ya registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {                  // Si ya existe el usuario...
                    $alertas = Usuario::getAlertas();
                } else {
                    // No está registrado , lo preparamos y almacenamos en BD
                    
                    // 1 - Hashear password
                    $usuario->hashPassword();
                    
                    // 2 - Generar un token único
                    $usuario->crearToken();

                    // 3 - Enviar email (instalamos extension PHPMailer con composer)
                    $email = new Email($usuario->nombre,$usuario->email,$usuario->token);

                    // 4 - Enviar confirmación
                    $email->enviarConfirmacion();

                    // Crear el registro (C del crud) una vez confirmado email
                    $resultado = $usuario->guardar();
           
                    if($resultado) {
                        header('Location: /mensaje');   // Mostramos mensaje de éxito
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    // Mensaje creación cuenta
    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }
    // Confirmacion de cuenta
    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);                 // Leemos y sanitizamos token
        $usuario = Usuario::where('token',$token);

        if(empty($usuario)) {

            // Mensaje de error
            Usuario::setAlerta('error','Token no válido');

        } else {

            //Mensaje de éxito
            $usuario->confirmado = '1';
            $usuario->token = null;             // Borramos el token
            $usuario->guardar();                // Almacenamos en BD
            Usuario::setAlerta('exito', 'Cuenta creada correctamente'); // Mensaje de éxito
        }
        // Leer alertas
        $alertas = Usuario::getAlertas();           // Lee las alertas que haya para pasarlas a la vista
        $router->render('auth/confirmar-cuenta',[
            'alertas' => $alertas
        ]);
    }

    // Reestablecer password olvidado
    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            // Si no hay errores
            if(empty($alertas)) {
                $usuario = Usuario::where('email',$auth->email);

                if($usuario && $usuario->confirmado === "1") {
                    // Existe y confirmado
                    // Generamos token de un solo uso
                    $usuario->crearToken();
                    $usuario->guardar();            // Guardo el token en el usuario en la bd

                    // Enviar el email
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();
                    // Alerta éxito
                    Usuario::setAlerta('exito','Revisa tu email');
                } else {
                    // No existe o no confirmado
                    Usuario::setAlerta('error','El usuario no existe o no está confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();               // leo alertas si hay

        $router->render('auth/olvide-password',[
            'alertas'=>$alertas
        ]);
    }

    // Reestablecer el password una vez confirmada la solicitud
    public static function recuperar(Router $router) {
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);
        // Busco usuario por su token
        $usuario = Usuario::where('token',$token);      // Busco usuario con ese token y veo si existe

        if(empty($usuario)) {
            Usuario::setAlerta('error','Token no válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer nuevo password y guardarlo

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            // Si pasa la validación
            if(empty($alertas)) {
                $usuario->password = null;      // borramos password anterior

                // asignamos el nuevo
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;          // Borramos token que ya fué usado
                
                $resultado = $usuario->guardar();
                // Si se guarda correctamente
                if($resultado) {
                    header('Location: /');      // Enviamos a inicio,página de login
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas'=>$alertas,
            'error'=>$error
        ]);
    }
}