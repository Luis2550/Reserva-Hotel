<?php
 $url_base = "http://localhost/hotel-reserva/";
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reservas de Hotel</title>
    <link rel="stylesheet" href="estilos/estilo.css">
</head>
<body>
    <header>
        <h1>Sistema de Reservas de Hotel</h1>
        <nav>
            <ul>
                <li><a href="<?php echo $url_base;?>index.php">Inicio</a></li>
                <li><a href="<?php echo $url_base;?>modulos/clientes/ver_cliente.php">Clientes</a></li>
                <li><a href="<?php echo $url_base?>modulos/productos/ver_producto.php">Productos</a></li>
                <li><a href="<?php echo $url_base?>modulos/reservas/ver_reservas.php">Reservas</a></li>
                <li><a href="<?php echo $url_base?>modulos/habitaciones/ver_habitacion.php">Habitaciones</a></li>
                <li><a href="<?php echo $url_base?>modulos/facturas/ver_facturas.php">Facturas</a></li>
                <li><a href="<?php echo $url_base?>/cerrar.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    
