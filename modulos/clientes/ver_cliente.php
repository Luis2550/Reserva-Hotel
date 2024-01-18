<?php
// Incluir el archivo de conexión
include '../../bd.php';

// Obtener cédulas de usuarios para el formulario
$sqlUsuarios = "SELECT cedula FROM usuarios";
$stmtUsuarios = $conn->query($sqlUsuarios);
$cedulasUsuarios = $stmtUsuarios->fetchAll(PDO::FETCH_COLUMN);

// Obtener roles para el formulario
$sqlRoles = "SELECT id_rol, nombre_rol FROM rol WHERE nombre_rol = 'clientes'";
$stmtRoles = $conn->query($sqlRoles);
$rol = $stmtRoles->fetch(PDO::FETCH_ASSOC);

// Mensaje de éxito
$exito = "";

// Manejar la inserción de un nuevo cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recuperar los datos del formulario
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
        $cedula = $_POST['cedula'];
        $edad = $_POST['edad'];
        $direccion = $_POST['direccion'];
        $id_rol = $rol['id_rol'];

        // Insertar el nuevo cliente en la base de datos
        $insert_sql = "INSERT INTO usuarios (nombres, apellidos, nombre_usuario, contrasena, cedula, edad, direccion, id_rol) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->execute([$nombres, $apellidos, $nombre_usuario, $contrasena, $cedula, $edad, $direccion, $id_rol]);

        // Mensaje de éxito
        $exito = "Cliente registrado exitosamente.";

        // Recargar la página
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        die("Error al registrar el cliente: " . $e->getMessage());
    }
}

// Eliminar cliente si se proporciona un ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
    $id_cliente = $_GET['eliminar'];
    try {
        // Realizar la eliminación del cliente
        $stmtEliminar = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmtEliminar->execute([$id_cliente]);

        // Mensaje de éxito
        $exito = "Cliente eliminado exitosamente.";

        // Recargar la página
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        die("Error al eliminar el cliente: " . $e->getMessage());
    }
}

// Realizar consulta a la tabla usuarios después de la inserción
$sqlConsulta = "SELECT * FROM usuarios";
$stmtConsulta = $conn->query($sqlConsulta);
$registros = $stmtConsulta->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
    <link rel="stylesheet" href="../../estilos/producto.css">
</head>
<body>

    <h2>Registro de Cliente</h2>

    <!-- Mensaje de éxito -->
    <?php if ($exito): ?>
        <p style="color: green;"><?php echo $exito; ?></p>
    <?php endif; ?>

    <!-- Agregar el formulario para insertar nuevos clientes -->
    <form method="post">
        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" required>

        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>

        <label for="cedula">Cédula:</label>
        <input type="text" name="cedula" required>

        <label for="edad">Edad:</label>
        <input type="number" name="edad" required>

        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" required>

        <label for="id_rol">Rol:</label>
        <input type="text" name="rol" value="Clientes" readonly>

        <button type="submit">Registrar Cliente</button>
    </form>
    
    <!-- Mostrar todos los registros -->
    <h3 class="title_3">Registros existentes:</h3>
    <table>
        <thead>
            <tr>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Nombre de Usuario</th>
                <th>Cedula</th>
                <th>Edad</th>
                <th>Direccion</th>
                <th>Acciones</th>
                <!-- Agrega más columnas según sea necesario -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $registro): ?>
                <tr>
                    <td><?php echo $registro['nombres']; ?></td>
                    <td><?php echo $registro['apellidos']; ?></td>
                    <td><?php echo $registro['nombre_usuario']; ?></td>
                    <td><?php echo $registro['cedula']; ?></td>
                    <td><?php echo $registro['edad']; ?></td>
                    <td><?php echo $registro['direccion']; ?></td>
                    <td>
                        <!-- Botón Editar -->
                        <a class="btn" href="editar_clientes.php?id=<?php echo $registro['id_usuario']; ?>">Editar</a>

                        <!-- Botón Eliminar -->
                        <a class="btn eliminar" href="?eliminar=<?php echo $registro['id_usuario']; ?>">Eliminar</a>
                    </td>
                    <!-- Agrega más celdas según sea necesario -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
