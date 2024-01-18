<?php
include '../../bd.php';

// Obtener cédulas de usuarios para el formulario
$sqlUsuarios = "SELECT cedula FROM usuarios";
$stmtUsuarios = $conn->query($sqlUsuarios);
$cedulasUsuarios = $stmtUsuarios->fetchAll(PDO::FETCH_COLUMN);

// Obtener datos de habitaciones para el formulario
$sqlHabitaciones = "SELECT id_habitacion, numero_habitacion, tipo_habitacion FROM habitaciones";
$stmtHabitaciones = $conn->query($sqlHabitaciones);
$habitaciones = $stmtHabitaciones->fetchAll(PDO::FETCH_ASSOC);

// Obtener el ID de la reserva a editar
if (isset($_GET['id'])) {
    $id_reserva = $_GET['id'];

    // Consultar la reserva a editar
    $sqlEditarReserva = "SELECT r.id_reserva, u.cedula, h.id_habitacion, r.fecha_inicio, r.fecha_fin, r.monto_total, r.pago_adelantado 
                        FROM reservas r
                        INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                        INNER JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
                        WHERE r.id_reserva = ?";
    $stmtEditarReserva = $conn->prepare($sqlEditarReserva);
    $stmtEditarReserva->execute([$id_reserva]);
    $reserva = $stmtEditarReserva->fetch(PDO::FETCH_ASSOC);

    if (!$reserva) {
        die("Reserva no encontrada.");
    }
} else {
    die("ID de reserva no proporcionado.");
}

// Manejar la actualización de la reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recuperar los datos del formulario
        $id_habitacion = $_POST['id_habitacion'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $pago_adelantado = $_POST['pago_adelantado'];

        // Obtener el ID de usuario correspondiente a la cédula
        $stmtUsuario = $conn->prepare("SELECT id_usuario FROM usuarios WHERE cedula = ?");
        $stmtUsuario->execute([$reserva['cedula']]);
        $id_usuario = $stmtUsuario->fetchColumn();

        // Verificar si ya existe una reserva para el mismo usuario, la misma habitación y las mismas fechas
        $stmtVerificarReserva = $conn->prepare("SELECT COUNT(*) FROM reservas WHERE id_usuario = ? AND id_habitacion = ? AND fecha_inicio = ? AND fecha_fin = ? AND id_reserva <> ?");
        $stmtVerificarReserva->execute([$id_usuario, $id_habitacion, $fecha_inicio, $fecha_fin, $id_reserva]);
        $reservasExistente = $stmtVerificarReserva->fetchColumn();

        if ($reservasExistente > 0) {
            die("Ya existe una reserva para el usuario en la habitación con las mismas fechas.");
        }

        // Actualizar la reserva en la base de datos
        $update_sql = "UPDATE reservas SET id_habitacion = ?, fecha_inicio = ?, fecha_fin = ?, pago_adelantado = ? WHERE id_reserva = ?";
        $stmtUpdate = $conn->prepare($update_sql);
        $stmtUpdate->execute([$id_habitacion, $fecha_inicio, $fecha_fin, $pago_adelantado, $id_reserva]);

        // Redireccionar a la página de ver_reservas.php
        header('Location: ver_reservas.php');
        exit();
    } catch (PDOException $e) {
        die("Error al actualizar la reserva: " . $e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva</title>
    <link rel="stylesheet" href="../../estilos/reservas.css">
</head>
<body>

    <h2>Editar Reserva</h2>

    <form method="post" action="editar.php?id=<?php echo $reserva['id_reserva']; ?>">
        <label for="cedula_usuario">Cédula del Usuario:</label>
        <input type="text" name="cedula_usuario" value="<?php echo $reserva['cedula']; ?>" readonly>

        <label for="id_habitacion">Habitación:</label>
        <select name="id_habitacion" required>
            <?php foreach ($habitaciones as $habitacion): ?>
                <option value="<?php echo $habitacion['id_habitacion']; ?>" <?php echo ($habitacion['id_habitacion'] == $reserva['id_habitacion']) ? 'selected' : ''; ?>>
                    <?php echo "Habitación {$habitacion['numero_habitacion']} - {$habitacion['tipo_habitacion']}"; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" value="<?php echo $reserva['fecha_inicio']; ?>" required>

        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" value="<?php echo $reserva['fecha_fin']; ?>" required>

        <label for="pago_adelantado">Pago Adelantado:</label>
        <input type="number" name="pago_adelantado" step="0.01" value="<?php echo $reserva['pago_adelantado']; ?>" required>

        <button type="submit">Guardar</button>
    </form>

</body>
</html>
