<?php
// Incluir el archivo de conexión
include '../../bd.php';

// Verificar si se proporciona un ID para la edición
if (isset($_GET['id'])) {
    $id_cliente = $_GET['id'];

    // Obtener los datos del cliente a editar
    $stmtEditar = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmtEditar->execute([$id_cliente]);
    $clienteEditar = $stmtEditar->fetch(PDO::FETCH_ASSOC);

    // Verificar si se ha enviado el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Recuperar los nuevos datos del formulario
            $nombres = $_POST['nombres'];
            $apellidos = $_POST['apellidos'];
            $nombre_usuario = $_POST['nombre_usuario'];
            $cedula = $_POST['cedula'];
            $edad = $_POST['edad'];
            $direccion = $_POST['direccion'];

            // Actualizar los datos del cliente en la base de datos
            $update_sql = "UPDATE usuarios SET nombres = ?, apellidos = ?, nombre_usuario = ?, cedula = ?, edad = ?, direccion = ? WHERE id_usuario = ?";
            $stmtUpdate = $conn->prepare($update_sql);
            $stmtUpdate->execute([$nombres, $apellidos, $nombre_usuario, $cedula, $edad, $direccion, $id_cliente]);

            // Redirigir a ver_cliente.php después de la edición
            header('Location: ver_cliente.php');
            exit();
        } catch (PDOException $e) {
            die("Error al actualizar el cliente: " . $e->getMessage());
        }
    }
} else {
    // Si no se proporciona un ID, redirigir a ver_cliente.php
    header('Location: ver_cliente.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="../../estilos/producto.css">
</head>
<body>

    <h2>Editar Cliente</h2>

    <!-- Formulario para editar el cliente -->
    <form method="post">
        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" value="<?php echo $clienteEditar['nombres']; ?>" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" value="<?php echo $clienteEditar['apellidos']; ?>" required>

        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" value="<?php echo $clienteEditar['nombre_usuario']; ?>" required>

        <label for="cedula">Cédula:</label>
        <input type="text" name="cedula" value="<?php echo $clienteEditar['cedula']; ?>" required>

        <label for="edad">Edad:</label>
        <input type="number" name="edad" value="<?php echo $clienteEditar['edad']; ?>" required>

        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" value="<?php echo $clienteEditar['direccion']; ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>

</body>
</html>
