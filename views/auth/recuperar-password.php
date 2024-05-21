<h1 class="nombre-pagina">Reestablecer Password</h1>
<p class="descripcion-pagina">Introduce tu nuevo password a continuación</p>

<!-- Mostrar Alertas de datos vacíos -->

<?php
    include_once __DIR__ . "/../templates/alertas.php";
?>

 <?php if($error) return; ?>    <!-- asi volvemos a introduccion de nuevo passwword -->

<form method="POST" class="formulario">
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            name="password"
            id="password"
            placeholder="Nuevo password"
        />
    </div>
    <input type="submit" class="boton" value="Guardar nuevo password">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? - Iniciar sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes cuenta? - Crea tu cuenta</a>
</div>