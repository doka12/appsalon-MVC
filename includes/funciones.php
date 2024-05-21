<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Revisa que usuario está autenticado
function isAuth() : void{
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

// Cálculo total a pagar
function esUltimo(string $actual, string $proximo): bool {
    if($actual !== $proximo) {
        return true;
    }
    return false;
}

// comprobar es admin ( y protejo la dirección )
function isAdmin() : void {
    if(!isset($_SESSION['admin'])) {
        header('Location: /');                  // Si no es enviamos a inicio
    }
}