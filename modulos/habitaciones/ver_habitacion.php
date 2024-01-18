<?php
// Incluir el archivo de conexión
include '../../bd.php';

// Obtener habitaciones para el formulario
$sqlHabitaciones = "SELECT * FROM habitaciones";
$stmtHabitaciones = $conn->query($sqlHabitaciones);
$habitaciones = $stmtHabitaciones->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Habitaciones</title>
    <link rel="stylesheet" href="../../estilos/habitacion.css">
</head>
<body>

    <h2>Tabla de Habitaciones</h2>

    <!-- Agregar el formulario para insertar nuevas habitaciones -->
    <form method="post">
        <label for="numero_habitacion">Número de Habitación:</label>
        <input type="text" name="numero_habitacion" required>

        <label for="tipo_habitacion">Tipo de Habitación:</label>
        <input type="text" name="tipo_habitacion" required>

        <label for="precio">Precio:</label>
        <input type="number" name="precio" step="0.01" max="5000" min="1" required>

        <button type="submit">Agregar Habitación</button>
    </form>

    <?php
    // Manejar la inserción de una nueva habitación
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Recuperar los datos del formulario
            $numero_habitacion = $_POST['numero_habitacion'];
            $tipo_habitacion = $_POST['tipo_habitacion'];
            $precio = $_POST['precio'];

            // Insertar la nueva habitación en la base de datos
            $insert_sql = "INSERT INTO habitaciones (numero_habitacion, tipo_habitacion, precio) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->execute([$numero_habitacion, $tipo_habitacion, $precio]);

            // Recargar la página
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            die("Error al insertar la habitación: " . $e->getMessage());
        }
    }

    // Eliminar habitación si se proporciona un ID
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
        $id_habitacion = $_GET['eliminar'];
        try {
            // Realizar la eliminación de la habitación
            $stmtEliminar = $conn->prepare("DELETE FROM habitaciones WHERE id_habitacion = ?");
            $stmtEliminar->execute([$id_habitacion]);
            
            // Recargar la página
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            die("Error al eliminar la habitación: " . $e->getMessage());
        }
    }

    // Realizar consulta a la tabla habitaciones después de la inserción o eliminación
    $sql = "SELECT * FROM habitaciones";
    $stmt = $conn->query($sql);
    $habitaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <table>
        <thead>
            <tr>
                <th>Número de Habitación</th>
                <th>Tipo de Habitación</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($habitaciones as $habitacion): ?>
                <tr>
                    <td><?php echo $habitacion['numero_habitacion']; ?></td>
                    <td><?php echo $habitacion['tipo_habitacion']; ?></td>
                    <td><?php echo $habitacion['precio']; ?></td>
                    <td>
                        <!-- Botón Editar -->
                        <a class="btn" href="editar_habitacion.php?id=<?php echo $habitacion['id_habitacion']; ?>">Editar</a>

                        <!-- Botón Eliminar -->
                        <a class="btn eliminar" href="?eliminar=<?php echo $habitacion['id_habitacion']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
