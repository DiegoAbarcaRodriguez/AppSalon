<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<?php 
    @include __DIR__.'/../templates/alerta.php';
?>

<?php
    if($error){
        return;
    }
?>

<form method="POST" class="formulario">
    <div class="campo">
        <label for="password">`Password</label>
        <input type="password" id="password" name="password" placeholder="Tu Nuevo Password">
    </div>

    <input type="submit" value="Guardar Nuevo Password" class="boton">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes Cuenta? Iniciar Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes cuenta? Obtener una</a>
</div>