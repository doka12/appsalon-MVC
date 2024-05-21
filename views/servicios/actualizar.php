<h1 class="nombre-pagina">Actualizar servicio</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>

<?php
    include_once __DIR__ . "/../templates/barra.php";
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form method="POST" class="formulario"> <!-- No ponemos action y nos envía a la misma página que estamos respetando el query string -->
    <?php include_once __DIR__ . "/formulario.php"; ?>
    <input type="submit" class="boton" value="Actualizar servicio">
</form>