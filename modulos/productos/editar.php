<?php
// Incluir el archivo de conexión
include '../../bd.php';

// Verificar si se proporciona un ID para la edición
if (isset($_GET['id'])) {
    $id_reserva = $_GET['id'];

    // Obtener los datos de la reserva a editar
    $stmtEditar = $conn->prepare("SELECT r.id_reserva, u.cedula, h.id_habitacion, h.numero_habitacion, h.tipo_habitacion, r.fecha_inicio, r.fecha_fin, r.monto_total, r.pago_adelantado
                                  FROM reservas r
                                  INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                                  INNER JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
                                  WHERE r.id_reserva = ?");
    $stmtEditar->execute([$id_reserva]);
    $reservaEditar = $stmtEditar->fetch(PDO::FETCH_ASSOC);

    // Verificar si se ha enviado el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Recuperar los nuevos datos del formulario
            $id_habitacion = $_POST['id_habitacion'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $pago_adelantado = $_POST['pago_adelantado'];

            // Actualizar los datos de la reserva en la base de datos
            $update_sql = "UPDATE reservas SET id_habitacion = ?, fecha_inicio = ?, fecha_fin = ?, pago_adelantado = ? WHERE id_reserva = ?";
            $stmtUpdate = $conn->prepare($update_sql);
            $stmtUpdate->execute([$id_habitacion, $fecha_inicio, $fecha_fin, $pago_adelantado, $id_reserva]);

            // Redirigir a ver_reservas.php después de la edición
            header('Location: ver_reservas.php');
            exit();
        } catch (PDOException $e) {
            die("Error al actualizar la reserva: " . $e->getMessage());
        }
    }
} else {
    // Si no se proporciona un ID, redirigir a ver_reservas.php
    header('Location: ver_reservas.php');
    exit();
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

    <!-- Formulario para editar la reserva -->
    <form method="post">
        <label for="cedula_usuario">Cédula del Usuario:</label>
        <input type="text" name="cedula_usuario" value="<?php echo $reservaEditar['cedula']; ?>" readonly>

        <label for="id_habitacion">Habitación:</label>
        <select name="id_habitacion" required>
            <?php
            // Obtener datos de habitaciones para el menú desplegable
            $sqlHabitaciones = "SELECT id_habitacion, numero_habitacion, tipo_habitacion FROM habitaciones";
            $stmtHabitaciones = $conn->query($sqlHabitaciones);
            $habitaciones = $stmtHabitaciones->fetchAll(PDO::FETCH_ASSOC);

            // Mostrar opciones en el menú desplegable
            foreach ($habitaciones as $habitacion) {
                echo "<option value='{$habitacion['id_habitacion']}' ";
                echo ($habitacion['id_habitacion'] == $reservaEditar['id_habitacion']) ? 'selected' : '';
                echo ">Habitación {$habitacion['numero_habitacion']} - {$habitacion['tipo_habitacion']}</option>";
            }
            ?>
        </select>

        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" value="<?php echo $reservaEditar['fecha_inicio']; ?>" required>

        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" value="<?php echo $reservaEditar['fecha_fin']; ?>" required>

        <label for="pago_adelantado">Pago Adelantado:</label>
        <input type="number" name="pago_adelantado" step="0.01" value="<?php echo $reservaEditar['pago_adelantado']; ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>

</body>
</html>
