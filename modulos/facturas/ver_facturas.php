<?php
// Incluir el archivo de conexión
include '../../bd.php';

// Obtener cédulas de usuarios para el formulario
$sqlUsuarios = "SELECT id_usuario, nombres, apellidos FROM usuarios";
$stmtUsuarios = $conn->query($sqlUsuarios);
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

// Variable para almacenar los datos del cliente seleccionado
$datosCliente = array();

// Manejar la selección de un cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario_seleccionado = $_POST['id_usuario'];

    // Obtener datos del usuario seleccionado, incluyendo la dirección
    $sqlDatosUsuario = "SELECT * FROM usuarios WHERE id_usuario = ?";
    $stmtDatosUsuario = $conn->prepare($sqlDatosUsuario);
    $stmtDatosUsuario->execute([$id_usuario_seleccionado]);
    $datosCliente['usuario'] = $stmtDatosUsuario->fetch(PDO::FETCH_ASSOC);

    // Modificar la consulta SQL de reservas para incluir el número y tipo de habitación
    $sqlReservas = "SELECT r.id_reserva, h.numero_habitacion, h.tipo_habitacion, r.fecha_inicio, r.fecha_fin, r.monto_total, r.pago_adelantado FROM reservas r
                    INNER JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
                    WHERE r.id_usuario = ?";
    $stmtReservas = $conn->prepare($sqlReservas);
    $stmtReservas->execute([$id_usuario_seleccionado]);
    $datosCliente['reservas'] = $stmtReservas->fetchAll(PDO::FETCH_ASSOC);

    // Obtener productos del usuario seleccionado
    $sqlProductos = "SELECT p.id_producto, p.nombre_producto, p.cantidad, p.precio, p.precio_total FROM productos p
                     WHERE p.id_usuario = ?";
    $stmtProductos = $conn->prepare($sqlProductos);
    $stmtProductos->execute([$id_usuario_seleccionado]);
    $datosCliente['productos'] = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Cliente</title>
    <link rel="stylesheet" href="../../estilos/facturas.css">
</head>
<body>

    <h2>Detalles del Cliente</h2>

    <!-- Formulario para seleccionar un cliente -->
    <form method="post">
        <label for="id_usuario">Seleccionar Cliente:</label>
        <select name="id_usuario" required>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?php echo $usuario['id_usuario']; ?>"><?php echo $usuario['nombres'] . ' ' . $usuario['apellidos']; ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Mostrar Detalles</button>
    </form>

    <?php
    // Mostrar los detalles del cliente si se ha seleccionado uno
    if (!empty($datosCliente)) {
        echo "<h3>Datos del Cliente</h3>";
        echo "<p>Nombre: {$datosCliente['usuario']['nombres']} {$datosCliente['usuario']['apellidos']}</p>";
        echo "<p>Cédula: {$datosCliente['usuario']['cedula']}</p>";
        echo "<p>Dirección: {$datosCliente['usuario']['direccion']}</p>"; // Nueva línea para mostrar la dirección
        // Agregar más campos según sea necesario

        echo "<h3>Reservas del Cliente</h3>";
        echo "<table>";
        echo "<thead><tr><th>Número de Habitación</th><th>Tipo de Habitación</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Monto Total</th><th>Pago Adelantado</th></tr></thead>";
        echo "<tbody>";
        foreach ($datosCliente['reservas'] as $reserva) {
            echo "<tr>";
         
            echo "<td>{$reserva['numero_habitacion']}</td>";
            echo "<td>{$reserva['tipo_habitacion']}</td>";
            echo "<td>{$reserva['fecha_inicio']}</td>";
            echo "<td>{$reserva['fecha_fin']}</td>";
            echo "<td>{$reserva['monto_total']}</td>";
            echo "<td>{$reserva['pago_adelantado']}</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";

        echo "<h3>Productos del Cliente</h3>";
        echo "<table>";
        echo "<thead><tr><th>Nombre Producto</th><th>Cantidad</th><th>Precio</th><th>Precio Total</th></tr></thead>";
        echo "<tbody>";
        foreach ($datosCliente['productos'] as $producto) {
            echo "<tr>";
        
            echo "<td>{$producto['nombre_producto']}</td>";
            echo "<td>{$producto['cantidad']}</td>";
            echo "<td>{$producto['precio']}</td>";
            echo "<td>{$producto['precio_total']}</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    ?>

<?php
// Calcular el total a pagar sumando el monto total original de la habitación y el precio total de productos
if (!empty($datosCliente['reservas']) || !empty($datosCliente['productos'])) {
    $totalHabitacionOriginal = 0;
    $adelantoReservas = 0;
    $totalProductos = 0;

    // Calcular el total original de la habitación y el adelanto de las reservas
    foreach ($datosCliente['reservas'] as $reserva) {
        $totalHabitacionOriginal += $reserva['monto_total'];
        $adelantoReservas += $reserva['pago_adelantado'];
    }

    // Calcular el total de productos
    foreach ($datosCliente['productos'] as $producto) {
        $totalProductos += $producto['precio_total'];
    }

    // Calcular el total a pagar sumando los resultados obtenidos
    $totalPagar = $totalHabitacionOriginal + $totalProductos - $adelantoReservas;
}
?>

<!-- ... (resto del código) -->

<h3>Total a Pagar</h3>
<table>
    <thead>
        <tr>
            <th>Total Habitación (Original)</th>
            <th>Adelanto Reservas</th>
            <th>Total Productos</th>
            <th>Total a Pagar</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $totalHabitacionOriginal; ?></td>
            <td><?php echo $adelantoReservas; ?></td>
            <td><?php echo $totalProductos; ?></td>
            <td><?php echo $totalPagar; ?></td>
        </tr>
    </tbody>
</table>


</body>
</html>
