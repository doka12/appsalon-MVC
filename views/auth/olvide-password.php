<h1 class="nombre-pagina">Olvidé mi password</h1>
<p class="descripcion-pagina">Reestablece tu password introduciendo tu Email</p>

<!-- Mostrar Alertas de datos vacíos -->

<?php
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/olvide" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            name="email"
            id="email"
            placeholder="Tu email"
        />
    </div>

    <input type="submit" value="Enviar instrucciones" class="boton">
</form>

<div class="acciones">
    <a href="/">¿ Ya tienes una cuenta ? - Inicia sesión</a>
    <a href="/crear-cuenta">¿ Aún no tienes una cuenta? - Crea una </a>
</div