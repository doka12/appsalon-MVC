<h1 class="nombre-pagina">Panel de Administracion</h1>

<?php
    include_once __DIR__ . "/../templates/barra.php";
?>

<h2>Buscar citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input
                type="date"
                id="fecha"
                nombre="fecha"
                value="<?php echo $fecha ?>"
            />
        </div>
    </form>
</div>

<?php
    if(count($citas) === 0) {
        echo "<h2>No hay citas en esta fecha</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">

        <!-- compruebo los id de las citas para ver que son distintas -->

        <?php
            $idCita = 0;                        // Para evitar un undefined inicializo la var aquí
            foreach( $citas as $key =>$cita) {
                if($idCita !==$cita->id) {      // Para no repetir los que tienen el mismo ID
                $total = 0;                     // Inicio a 0 y aquí tiene que ser,después de ver que id es distinto 
        ?>
                
                <!-- HTML de datos de cada cita -->

        <li>
            <p>ID: <span><?php echo $cita->id; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>
            <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
            <p>Email: <span><?php echo $cita->email; ?></span></p>
            <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p>

                    <!-- HTML de los servicios de cada cita -->

            <h3>Servicios</h3>

            <?php 
                $idCita = $cita->id;                // Guardamos el id para luego comprobar si es igual o no
                 }                                  // Fin If ( lo hacemos aquí porque necesitamos que si muestre todos los servicios)
                 $total += $cita->precio;           // Sumo coste de servicio al total,después del IF
            ?>
            <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio ;?>€</p>

            <!-- total de los servicios a pagar -->
        <?php
            $actual = $cita->id;                            // Dice id en el que estamos
            $proximo = $citas[$key + 1]->id ?? 0;           // Dice el próximo si lo hay o asigna 0
        
            if(esUltimo($actual,$proximo)) { ?>
                <p class="total">Total: <span><?php echo $total?>€</p>

                <!-- boton para eliminar citas individualmente -->
                <form action="/api/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $cita->id?>">
                    <input type="submit" value="eliminar" class="boton-eliminar">
                </form>
     
        <!-- </li> así lo cierra HTML por sí mismo y no desalinea el primer li-->

        <?php 
            }
          }   // Fin foreach 
        ?>
        
    </ul>
   
</div>

<?php 
    $script = "<script src='build/js/buscador.js'></script>";
?>