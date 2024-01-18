<?php
// Incluir el archivo de conexión
include '../../bd.php';

// Obtener cédulas de usuarios para el formulario
$sqlUsuarios = "SELECT cedula FROM usuarios";
$stmtUsuarios = $conn->query($sqlUsuarios);
$cedulasUsuarios = $stmtUsuarios->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Productos</title>
    <link rel="stylesheet" href="../../estilos/producto.css">
</head>
<body>

    <h2>Tabla de Productos</h2>

    <!-- Agregar el formulario para insertar nuevos productos -->
    <form method="post">
        <label for="cedula_usuario">Cédula del Usuario:</label>
        <select name="cedula_usuario" required>
            <?php foreach ($cedulasUsuarios as $cedula): ?>
                <option value="<?php echo $cedula; ?>"><?php echo $cedula; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="nombre_producto">Nombre Producto:</label>
        <input type="text" name="nombre_producto" required>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" required>

        <label for="precio">Precio:</label>
        <input type="number" name="precio" step="0.01" required>

        <button type="submit">Agregar Producto</button>
    </form>

    <?php
    // Manejar la inserción de un nuevo producto
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Recuperar los datos del formulario
            $cedula_usuario = $_POST['cedula_usuario'];
            $nombre_producto = $_POST['nombre_producto'];
            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];

            // Calcular el precio total
            $precio_total = $cantidad * $precio;

            // Obtener el ID de usuario correspondiente a la cédula
            $stmtUsuario = $conn->prepare("SELECT id_usuario FROM usuarios WHERE cedula = ?");
            $stmtUsuario->execute([$cedula_usuario]);
            $id_usuario = $stmtUsuario->fetchColumn();

            // Insertar el nuevo producto en la base de datos
            $insert_sql = "INSERT INTO productos (id_usuario, nombre_producto, cantidad, precio, precio_total) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->execute([$id_usuario, $nombre_producto, $cantidad, $precio, $precio_total]);

            // Recargar la página
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            die("Error al insertar el producto: " . $e->getMessage());
        }
    }

    // Eliminar producto si se proporciona un ID
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
        $id_producto = $_GET['eliminar'];
        try {
            // Realizar la eliminación del producto
            $stmtEliminar = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
            $stmtEliminar->execute([$id_producto]);
            
            // Recargar la página
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            die("Error al eliminar el producto: " . $e->getMessage());
        }
    }

    // Realizar consulta a la tabla productos después de la inserción o eliminación
    $sql = "SELECT p.id_producto, u.cedula, p.nombre_producto, p.cantidad, p.precio, p.precio_total FROM productos p
            INNER JOIN usuarios u ON p.id_usuario = u.id_usuario";
    $stmt = $conn->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <table>
        <thead>
            <tr>
                <th>Cédula del Usuario</th>
                <th>Nombre Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Precio Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo $producto['cedula']; ?></td>
                    <td><?php echo $producto['nombre_producto']; ?></td>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td><?php echo $producto['precio']; ?></td>
                    <td><?php echo $producto['precio_total']; ?></td>
                    <td>
                        <!-- Botón Editar -->
                        <a class="btn" href="editar.php?id=<?php echo $producto['id_producto']; ?>">Editar</a>

                        <!-- Botón Eliminar -->
                        <a class="btn eliminar" href="?eliminar=<?php echo $producto['id_producto']; ?>">Eliminar</a>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
