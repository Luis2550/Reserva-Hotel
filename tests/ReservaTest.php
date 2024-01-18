<?php
// ReservaTest.php

use PHPUnit\Framework\TestCase;
use modulos\ReservaHandler;

class ReservaTest extends TestCase
{
    public function testFechaReservaInvalida()
    {
        // Fechas para la prueba
        $fechaInicio = '2023-01-01';
        $fechaFin = '2022-12-31';

        // Monto total y pago adelantado válidos
        $montoTotal = 100;
        $pagoAdelantado = 20;

        // Crear instancia de ReservaHandler
        $reservaHandler = new ReservaHandler($mockConn);

        // Intentar agregar reserva con fecha inválida
        $result = $reservaHandler->agregarReserva('cedula', 'idHabitacion', $fechaInicio, $fechaFin, $montoTotal, $pagoAdelantado);

        // Afirmar que el resultado es falso (la fecha es inválida)
        $this->assertFalse($result);
    }

    public function testMontoTotalInvalido()
    {
        // Fechas para la prueba
        $fechaInicio = '2023-01-01';
        $fechaFin = '2023-01-10';

        // Monto total inválido (igual a cero)
        $montoTotalInvalido = 0;

        // Pago adelantado válido
        $pagoAdelantado = 30;

        // Crear instancia de ReservaHandler
        $reservaHandler = new ReservaHandler($mockConn);

        // Intentar agregar reserva con monto total inválido
        $result = $reservaHandler->agregarReserva('cedula', 'idHabitacion', $fechaInicio, $fechaFin, $montoTotalInvalido, $pagoAdelantado);

        // Afirmar que el resultado es falso (el monto total es inválido)
        $this->assertFalse($result);
    }
    
}

?>