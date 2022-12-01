<h1 class="nombre-pagina">Nuevo Servicio</h1>
<p class="descripcion-pagina">Llena todo los campos para crear un nuevo servicio</p>

<?php 
    include_once __DIR__.'/../templates/barra.php';
    include_once __DIR__.'/../templates/alerta.php';
?>

<form action="/servicios/crear" method="post">
    <?php include_once __DIR__.'/formulario.php'?>
    <input type="submit" class="boton" value="Guardar Servicio">
</form>