<?php

use PHPUnit\Framework\TestCase;
use modulos\HabitacionHandler;

class HabitacionTest extends TestCase
{
    public function testInsertHabitacion()
    {
        // Crear un mock para la conexión a la base de datos
        $mockConn = $this->createMock(PDO::class);

        // Configurar el comportamiento esperado para la conexión a la base de datos
        // ...

        // Crear una instancia de la clase que maneja habitaciones
        $habitacionHandler = new HabitacionHandler($mockConn);

        // Llamar al método que deseas probar
        $result = $habitacionHandler->insertHabitacion('101', 'Sencilla', 100.00);

        // Afirmar que el resultado es el esperado
        $this->assertTrue($result);
    }

    public function testCalculateTotalPrice()
    {
        // Crear un mock para la conexión a la base de datos
        $mockConn = $this->createMock(PDO::class);

        // Configurar el comportamiento esperado para la conexión a la base de datos
        // ...

        // Crear una instancia de la clase que maneja habitaciones
        $habitacionHandler = new HabitacionHandler($mockConn);

        // Llamar al método que deseas probar
        $result = $habitacionHandler->calculateTotalPrice(2, 120.00);

        // Afirmar que el resultado es el esperado
        $this->assertEquals(240.00, $result);
    }
}
?>