<?php

namespace Tests\Unit\Services;

use App\Services\TorneoService;
use App\Models\Jugador;
use App\Models\Torneo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TorneoServiceTest extends TestCase
{
    use RefreshDatabase;

    private TorneoService $torneoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->torneoService = new TorneoService();
    }

    public function testSimularTorneoConDosJugadores()
    {
        $jugadoresData = [
            ['nombre' => 'Jugador1', 'nivel_habilidad' => 80, 'genero' => 'masculino', 'fuerza' => 70, 'velocidad' => 75],
            ['nombre' => 'Jugador2', 'nivel_habilidad' => 75, 'genero' => 'masculino', 'fuerza' => 65, 'velocidad' => 80],
        ];

        $resultado = $this->torneoService->simularYGuardarTorneo($jugadoresData);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('ganador', $resultado);
        $this->assertCount(2, $resultado['participantes']);
    }

    public function testSimularTorneoConCuatroJugadores()
    {
        $jugadoresData = [
            ['nombre' => 'Jugador1', 'nivel_habilidad' => 80, 'genero' => 'masculino', 'fuerza' => 70, 'velocidad' => 75],
            ['nombre' => 'Jugador2', 'nivel_habilidad' => 75, 'genero' => 'masculino', 'fuerza' => 65, 'velocidad' => 80],
            ['nombre' => 'Jugador3', 'nivel_habilidad' => 85, 'genero' => 'masculino', 'fuerza' => 75, 'velocidad' => 70],
            ['nombre' => 'Jugador4', 'nivel_habilidad' => 70, 'genero' => 'masculino', 'fuerza' => 60, 'velocidad' => 85],
        ];

        $resultado = $this->torneoService->simularYGuardarTorneo($jugadoresData);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('ganador', $resultado);
        $this->assertCount(4, $resultado['participantes']);
        $this->assertInstanceOf(Torneo::class, Torneo::find($resultado['id']));
    }

    public function testSimularTorneoConNumeroDejugadoresInvalido()
    {
        $this->expectException(\InvalidArgumentException::class);

        $jugadoresData = [
            ['nombre' => 'Jugador1', 'nivel_habilidad' => 80, 'genero' => 'masculino', 'fuerza' => 70, 'velocidad' => 75],
            ['nombre' => 'Jugador2', 'nivel_habilidad' => 75, 'genero' => 'masculino', 'fuerza' => 65, 'velocidad' => 80],
            ['nombre' => 'Jugador3', 'nivel_habilidad' => 85, 'genero' => 'masculino', 'fuerza' => 75, 'velocidad' => 70],
        ];

        $this->torneoService->simularYGuardarTorneo($jugadoresData);
    }

    public function testSimularTorneoConJugadoresDeDiferentesGeneros()
    {
        $this->expectException(\InvalidArgumentException::class);

        $jugadoresData = [
            ['nombre' => 'Jugador1', 'nivel_habilidad' => 80, 'genero' => 'masculino', 'fuerza' => 70, 'velocidad' => 75],
            ['nombre' => 'Jugador2', 'nivel_habilidad' => 75, 'genero' => 'femenino', 'tiempo_reaccion' => 50],
            ['nombre' => 'Jugador3', 'nivel_habilidad' => 85, 'genero' => 'masculino', 'fuerza' => 75, 'velocidad' => 80],
            ['nombre' => 'Jugador4', 'nivel_habilidad' => 70, 'genero' => 'femenino', 'tiempo_reaccion' => 45],
        ];

        $this->torneoService->simularYGuardarTorneo($jugadoresData);
    }

}
