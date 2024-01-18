<?php

namespace modulos;

class HabitacionHandler
{
    private $conn; // La conexión a la base de datos u otro recurso necesario

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function insertHabitacion($numero_habitacion, $tipo_habitacion, $precio)
{
    try {
        $query = "INSERT INTO habitaciones (numero_habitacion, tipo_habitacion, precio) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $numero_habitacion, $tipo_habitacion, $precio);
        
        // Ejecutar la consulta preparada
        $stmt->execute();

        // Retorna true si la inserción fue exitosa
        return true;
    } catch (\Exception $e) {
        // Puedes manejar el error de alguna manera, por ejemplo, loguearlo
        error_log("Error en insertHabitacion: " . $e->getMessage());
        return false;
    }
}




    public function calculateTotalPrice($cantidad, $precio)
    {
        // Lógica para calcular el precio total de una habitación
        // ...

        // Retorna el precio total calculado
        return $cantidad * $precio;
    }

    // Otros métodos relacionados con el manejo de habitaciones pueden ir aquí
}
?>