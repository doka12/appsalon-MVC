<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Rellena el formulario para crea una cuenta</p>

<!-- Mostrar Alertas de datos vacíos -->

<?php
    include_once __DIR__ . "/../templates/alertas.php";
?>

<!-- Formulario -->

<form class="formulario" method="POST" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text" 
            id="nombre" 
            name="nombre" 
            placeholder="Nombre"
            value="<?php echo s($usuario->nombre); ?>"     
        />
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input 
            type="text" 
            id="apellido" 
            name="apellido" 
            placeholder="Apellido"
            value="<?php echo s($usuario->apellido); ?>"
        />
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input 
            type="tel" 
            id="telefono" 
            name="telefono" 
            placeholder="Telefono"
            value="<?php echo s($usuario->telefono); ?>"
        />
    </div>

    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            placeholder="Email"
            value="<?php echo s($usuario->email);?>"
        />
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="Password"
        />
    </div>

    <input type="submit" value="Crear cuenta" class="boton">

</form>

    <div class="acciones">
        <a href="/">¿ Ya tienes una cuenta ? - Inicia sesión </a>
        <a href="/olvide">Olvidé mi password</a>
    </div>