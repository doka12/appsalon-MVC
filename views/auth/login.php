<h1 class="nombre-pagina">LOGIN</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<!-- Mostrar Alertas de datos vacíos -->

<?php
    include_once __DIR__ . "/../templates/barra.php";
?>

<form action="/" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Introduce tu email" /> <!--  $_POST['email'] enviará   -->
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Introduce tu password" /> <!--  $_POST['password'] enviar   -->
    </div>

    <input type="submit" class="boton" value="Iniciar sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Crea tu cuenta gratuita</a>
    <a href="/olvide">Olvidé mi password</a>
</div>