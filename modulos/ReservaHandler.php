<?php
// ReservaHandler.php

namespace modulos;

use PDO;

class ReservaHandler
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function agregarReserva($cedula, $idHabitacion, $fechaInicio, $fechaFin, $montoTotal, $pagoAdelantado)
    {
        // Validar la fecha de fin
        if (strtotime($fechaFin) <= strtotime($fechaInicio)) {
            return false; // La fecha de fin debe ser posterior a la fecha de inicio
        }

        // Validar el monto total
        if ($montoTotal <= 0) {
            return false; // El monto total debe ser mayor que cero
        }

        // Lógica para agregar la reserva a la base de datos
        // ...

        return true; // Reserva agregada con éxito
    }
}

?>
