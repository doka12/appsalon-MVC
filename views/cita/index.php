<h1 class="nombre-pagina">Crear nueva cita</h1>
<p class="descripcion-pagina">Elige tus servicios e introduce tus datos</p>

<?php 
    include_once __DIR__ . '/../templates/barra.php';
;?>

<!-- principal -->

<div id="app">
    <!-- tabs -->
    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>
    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios"></div>            <!-- Aquí inyecto con js los datos desde la bd-->
    </div>
    <div id="paso-2" class="seccion">
        <h2>Tus datos y cita</h2>
        <p class="text-center">Introduce tus datos y fecha de tu cita</p>

        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                    <input
                        id="nombre" 
                        type="text"
                        placeholder="Tu nombre"
                        value="<?php echo $nombre; ?>"
                        disabled
                    />
                
            </div>

            <div class="campo">
                <label for="fecha">Fecha</label>        <!-- Fecha establecemos dede el dia siguiente y no valen anteriores, con min etc   -->
                    <input
                        id="fecha" 
                        type="date"
                        min="<?php echo date('Y-m-d'), strtotime('+1 day');?>" 
                    />
                
            </div>

            <div class="campo">
                <label for="hora">Hora</label>
                    <input
                        id="hora" 
                        type="time"
                    />
                
            </div>
            <!-- campo oculto par usuarioId -->
            <input type="hidden" id="id" value="<?php echo $id; ?>">
        </form>
    </div>
    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la informacion sea correcta</p>
    </div>

        <!-- paginación -->

    <div class="paginacion">
        <button
            id="anterior"
            class="boton"
        >&laquo; Anterior</button>
        
        <button
            id="siguiente"
            class="boton"
        > Siguiente &raquo;</button>
    </div>
    
</div>

<?php
    $script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='build/js/app.js'></script>
    ";
?>