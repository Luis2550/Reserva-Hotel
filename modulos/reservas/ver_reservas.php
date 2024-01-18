<?php
// Incluir el archivo de conexión
include '../../bd.php';

// Obtener cédulas de usuarios para el formulario
$sqlUsuarios = "SELECT cedula FROM usuarios";
$stmtUsuarios = $conn->query($sqlUsuarios);
$cedulasUsuarios = $stmtUsuarios->fetchAll(PDO::FETCH_COLUMN);

// Obtener datos de habitaciones para el formulario
$sqlHabitaciones = "SELECT id_habitacion, numero_habitacion, tipo_habitacion FROM habitaciones";
$stmtHabitaciones = $conn->query($sqlHabitaciones);
$habitaciones = $stmtHabitaciones->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Reservas</title>
    <link rel="stylesheet" href="../../estilos/reservas.css">
</head>
<body>

    <h2>Tabla de Reservas</h2>

    <!-- Agregar el formulario para insertar nuevas reservas -->
    <form method="post">
        <label for="cedula_usuario">Cédula del Usuario:</label>
        <select name="cedula_usuario" required>
            <?php foreach ($cedulasUsuarios as $cedula): ?>
                <option value="<?php echo $cedula; ?>"><?php echo $cedula; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="id_habitacion">Habitación:</label>
        <select name="id_habitacion" required>
            <?php foreach ($habitaciones as $habitacion): ?>
                <option value="<?php echo $habitacion['id_habitacion']; ?>">
                    <?php echo "Habitación {$habitacion['numero_habitacion']} - {$habitacion['tipo_habitacion']}"; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" required>

        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" required>

        <label for="pago_adelantado">Pago Adelantado:</label>
        <input type="number" name="pago_adelantado" step="0.01" required>

        <button type="submit">Agregar Reserva</button>
    </form>

    <?php
    // Manejar la inserción de una nueva reserva
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Recuperar los datos del formulario
            $cedula_usuario = $_POST['cedula_usuario'];
            $id_habitacion = $_POST['id_habitacion'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $monto_total = $_POST['monto_total'];
            $pago_adelantado = $_POST['pago_adelantado'];

            // Obtener el ID de usuario correspondiente a la cédula
            $stmtUsuario = $conn->prepare("SELECT id_usuario FROM usuarios WHERE cedula = ?");
            $stmtUsuario->execute([$cedula_usuario]);
            $id_usuario = $stmtUsuario->fetchColumn();

            // Insertar la nueva reserva en la base de datos
            $insert_sql = "INSERT INTO reservas (id_usuario, id_habitacion, fecha_inicio, fecha_fin, monto_total, pago_adelantado) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->execute([$id_usuario, $id_habitacion, $fecha_inicio, $fecha_fin, $monto_total, $pago_adelantado]);

            // Recargar la página
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            die("Error al insertar la reserva: " . $e->getMessage());
        }
    }

    // Eliminar reserva si se proporciona un ID
    // Eliminar reserva si se proporciona un ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
    $id_reserva = $_GET['eliminar'];
    try {
        // Eliminar facturas asociadas a la reserva
        $stmtEliminarFacturas = $conn->prepare("DELETE FROM facturas WHERE id_reserva = ?");
        $stmtEliminarFacturas->execute([$id_reserva]);

        // Luego, eliminar la reserva
        $stmtEliminarReserva = $conn->prepare("DELETE FROM reservas WHERE id_reserva = ?");
        $stmtEliminarReserva->execute([$id_reserva]);

        // Recargar la página
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        die("Error al eliminar la reserva: " . $e->getMessage());
    }
}


    // Realizar consulta a la tabla reservas después de la inserción o eliminación
    $sql = "SELECT r.id_reserva, u.cedula, h.tipo_habitacion, r.fecha_inicio, r.fecha_fin, r.monto_total, r.pago_adelantado FROM reservas r
            INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
            INNER JOIN habitaciones h ON r.id_habitacion = h.id_habitacion";
    $stmt = $conn->query($sql);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <table>
        <thead>
            <tr>
                <th>Cédula del Usuario</th>
                <th>Habitación</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Monto Total</th>
                <th>Pago Adelantado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservas as $reserva): ?>
                <tr>
                    <td><?php echo $reserva['cedula']; ?></td>
                    <td><?php echo $reserva['tipo_habitacion']; ?></td>
                    <td><?php echo $reserva['fecha_inicio']; ?></td>
                    <td><?php echo $reserva['fecha_fin']; ?></td>
                    <td><?php echo $reserva['monto_total']; ?></td>
                    <td><?php echo $reserva['pago_adelantado']; ?></td>
                    <td>
                        <!-- Botón Editar -->
                        <a class="btn" href="editar.php?id=<?php echo $reserva['id_reserva']; ?>">Editar</a>

                        <!-- Botón Eliminar -->
                        <a class="btn eliminar" href="?eliminar=<?php echo $reserva['id_reserva']; ?>">Eliminar</a>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
