<?php
// Incluir el archivo de conexión
include '../../bd.php';

// Verificar si se proporciona un ID para la edición
if (isset($_GET['id'])) {
    $id_habitacion = $_GET['id'];

    // Obtener los datos de la habitación a editar
    $stmtEditar = $conn->prepare("SELECT * FROM habitaciones WHERE id_habitacion = ?");
    $stmtEditar->execute([$id_habitacion]);
    $habitacionEditar = $stmtEditar->fetch(PDO::FETCH_ASSOC);

    // Verificar si se ha enviado el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Recuperar los nuevos datos del formulario
            $numero_habitacion = $_POST['numero_habitacion'];
            $tipo_habitacion = $_POST['tipo_habitacion'];
            $precio = $_POST['precio'];

            // Actualizar los datos de la habitación en la base de datos
            $update_sql = "UPDATE habitaciones SET numero_habitacion = ?, tipo_habitacion = ?, precio = ? WHERE id_habitacion = ?";
            $stmtUpdate = $conn->prepare($update_sql);
            $stmtUpdate->execute([$numero_habitacion, $tipo_habitacion, $precio, $id_habitacion]);

            // Redirigir a ver_habitacion.php después de la edición
            header('Location: ver_habitacion.php');
            exit();
        } catch (PDOException $e) {
            die("Error al actualizar la habitación: " . $e->getMessage());
        }
    }
} else {
    // Si no se proporciona un ID, redirigir a ver_habitacion.php
    header('Location: ver_habitacion.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Habitación</title>
    <link rel="stylesheet" href="../../estilos/habitacion.css">
</head>
<body>

    <h2>Editar Habitación</h2>

    <!-- Formulario para editar la habitación -->
    <form method="post">
        <label for="numero_habitacion">Número de Habitación:</label>
        <input type="text" name="numero_habitacion" value="<?php echo $habitacionEditar['numero_habitacion']; ?>" required>

        <label for="tipo_habitacion">Tipo de Habitación:</label>
        <input type="text" name="tipo_habitacion" value="<?php echo $habitacionEditar['tipo_habitacion']; ?>" required>

        <label for="precio">Precio:</label>
        <input type="number" name="precio" step="0.01" max="5000" min="1" value="<?php echo $habitacionEditar['precio']; ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>

</body>
</html>
