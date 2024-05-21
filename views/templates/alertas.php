<?php

foreach($alertas as $key => $mensajes):        // Accedemos a la clave error
    foreach($mensajes as $mensaje):            // Dentro de la clave a los mensajes que haya
?>
    <div class="alerta <?php echo $key; ?>">   <!-- No sanitizo $key pues lo creo yo, no un usuario  -->
        <?php echo $mensaje; ?>
    </div>  
<?php
    endforeach;
endforeach;

?>