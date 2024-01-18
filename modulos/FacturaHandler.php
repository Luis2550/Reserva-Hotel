<?php

// En modulos/FacturaHandler.php

namespace modulos;

class FacturaHandler
{
    private $conn; // La conexión a la base de datos u otro recurso necesario

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function calcularTotalPagar($datosCliente)
    {
        $totalHabitacionOriginal = 0;
        $adelantoReservas = 0;
        $totalProductos = 0;

        // Calcular el total original de la habitación y el adelanto de las reservas
        foreach ($datosCliente['reservas'] as $reserva) {
            $totalHabitacionOriginal += $reserva['monto_total'];
            $adelantoReservas += $reserva['pago_adelantado'];
        }

        // Calcular el total de productos
        foreach ($datosCliente['productos'] as $producto) {
            $totalProductos += $producto['precio_total'];
        }

        // Calcular el total a pagar sumando los resultados obtenidos
        $totalPagar = $totalHabitacionOriginal + $totalProductos - $adelantoReservas;

        return $totalPagar;
    }
}
?>