<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    // Atributos
    public $email;
    public $nombre;
    public $token;


    public function __construct($nombre, $email, $token) {
        $this->nombre = ucfirst($nombre);           // Primera letra en mayúscula
        $this->email = $email;
        $this->token= $token;
    }
    // Enviar confirmación
    public function enviarConfirmacion() {
        // Creamos el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();            // Protocolo de envío de email
        
        // Datos de mailtrap para PHPmailer
       
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];      // Usando vars de entorno

        $mail->setFrom('cuentas@appsalon.com');     // Quien lo envía
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');  // De quien y dominio
        $mail->Subject = 'Confirma tu cuenta';

        // Set HTML para decirle que es html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';               // Conjunto de caracteres usado
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App Salón, sólo
        debes confirmarla presionando el siguiente enlace. </p>";
        $contenido .="<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/confirmar-cuenta?token="
        . $this->token . "'>Confirmar cuenta</a></p>";            // Así confirmamos que es quien debe ser
        $contenido .="<p> Si tú no solicitaste esta cuenta puedes ignorar éste mensaje. ";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        
        // Enviamos el mail
        $mail->send();
    }

    public function enviarInstrucciones() {
        // Creamos el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();            // Protocolo de envío de email
        
        // Datos de mailtrap para PHPmailer
       
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];      // Usando vars de entorno

        $mail->setFrom('cuentas@appsalon.com');     // Quien lo envía
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');  // De quien y dominio
        $mail->Subject = 'Reestablece tu password';

        // Set HTML para decirle que es html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';               // Conjunto de caracteres usado
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado reestablecer tu password,
        sigue el siguiente enlace para hacerlo</p>";
        $contenido .="<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/recuperar?token="
        . $this->token . "'>Reestablecer password</a></p>";            // Así confirmamos que es quien debe ser
        $contenido .="<p> Si tú no lo solicitaste puedes ignorar éste mensaje. ";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        
        // Enviamos el mail
        $mail->send();
    }
}