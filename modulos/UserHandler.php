<?php
namespace modulos;

class UserHandler
{
    private $conn; // La conexión a la base de datos u otro recurso necesario

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function registerUser($nombres, $apellidos, $nombre_usuario, $contrasena, $cedula, $edad, $direccion, $id_rol)
{
    try {
        // ... código de inserción aquí ...
        return "Usuario registrado exitosamente.";
    } catch (PDOException $e) {
        throw new \Exception("Error al registrar el usuario: " . $e->getMessage());
    }
}

public function deleteUser($id_usuario)
{
    try {
        // ... código de eliminación aquí ...
        return "Usuario eliminado exitosamente.";
    } catch (PDOException $e) {
        throw new \Exception("Error al eliminar el usuario: " . $e->getMessage());
    }
}

}
?>