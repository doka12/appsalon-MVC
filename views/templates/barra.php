<!-- Mostramos nombre del cliente, si no existe asignamos '' -->

<div class="barra">
    <p>Hola <?php echo $nombre ?? '';?></p>
    <a href="/logout" class="boton">Cerrar sesión</a>
</div>

<!-- botones para administración -->

<?php if(isset($_SESSION['admin'])) { // solo se muestra si inicio sesión un administrador ?>
    <div class="barra-servicios">
        <a href="/admin" class="boton">Ver citas</a>
        <a href="/servicios" class="boton">Ver servicios</a>
        <a href="/servicios/crear" class="boton">Nuevo servicio</a>
    </div>
 <?php } ?>     <!-- cierre if      -->