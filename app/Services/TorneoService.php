<?php

namespace App\Services;

use App\Models\Jugador;
use App\Models\Torneo;
use DateTime;
use App\Models\JugadorMasculino;
use App\Models\JugadorFemenino;

class TorneoService
{


    // Configuracion de los porcentajes de cada atributo para el calculo del puntaje
    private const PORCENTAJE_HABILIDAD = 0.6;
    private const PORCENTAJE_SUERTE = 0.1;
    private const PORCENTAJE_FUERZA = 0.15;
    private const PORCENTAJE_VELOCIDAD = 0.15;
    private const PORCENTAJE_TIEMPO_REACCION = 0.3;


    public function simularTorneo(array $jugadores)
    {
        

        $ronda = $jugadores;

        while (count($ronda) > 1) {
            $siguienteRonda = [];
            for ($i = 0; $i < count($ronda); $i += 2) {
                $ganador = $this->simularEnfrentamiento($ronda[$i], $ronda[$i + 1]);
                $siguienteRonda[] = $ganador;
            }
            $ronda = $siguienteRonda;
        }

        return $ronda[0];
    }

    private function simularEnfrentamiento(Jugador $jugador1, Jugador $jugador2): Jugador
    {
        do {
            $puntaje1 = $this->calcularPuntaje($jugador1);
            $puntaje2 = $this->calcularPuntaje($jugador2);
        } while ($puntaje1 === $puntaje2);

        return $puntaje1 > $puntaje2 ? $jugador1 : $jugador2;
    }

    private function calcularPuntaje(Jugador $jugador): float
    {
        $puntajeBase = $jugador->getNivelHabilidad() * self::PORCENTAJE_HABILIDAD + 
                       mt_rand(0, 100) * self::PORCENTAJE_SUERTE;

        if ($jugador instanceof JugadorMasculino) {
            $puntajeBase += $jugador->getFuerza() * self::PORCENTAJE_FUERZA + 
                            $jugador->getVelocidad() * self::PORCENTAJE_VELOCIDAD;
        } elseif ($jugador instanceof JugadorFemenino) {
            $puntajeBase += (100 - $jugador->getTiempoReaccion()) * self::PORCENTAJE_TIEMPO_REACCION;
        }

        return $puntajeBase;
    }

    private function esPotenciaDeDos(int $numero): bool
    {
        return ($numero & ($numero - 1)) === 0;
    }

    public function simularYGuardarTorneo(array $jugadoresData)
    {
        $numJugadores = count($jugadoresData);
        if ($numJugadores < 2 || !$this->esPotenciaDeDos($numJugadores)) {
            throw new \InvalidArgumentException("El número de jugadores debe ser una potencia de 2 (2, 4, 8, 16, etc.).");
        }

        $generoEsperado = $jugadoresData[0]['genero'];

        foreach ($jugadoresData as $jugador) {
            if ($jugador['genero'] !== $generoEsperado) {
                throw new \InvalidArgumentException("Todos los jugadores deben ser del mismo género.");
            }
        }
        $jugadores = $this->crearJugadores($jugadoresData);
        
        $torneo = Torneo::create([
            'fecha' => now()->format('Y-m-d'),
            'genero' => $jugadores[0]->genero,
        ]);
        
        $jugadoresIds = [];
        foreach ($jugadores as $jugador) {
            $jugador->save();
            $jugadoresIds[] = $jugador->id;
        }
        
        $torneo->participantes()->attach($jugadoresIds);
        
        $ganador = $this->simularTorneo($jugadores);
        
        $torneo->ganador()->associate($ganador);
        $torneo->save();
        
        $respuesta = [
            'id' => $torneo->id,
            'fecha' => $torneo->fecha,
            'genero' => $torneo->genero,
            'ganador' => $this->formatearJugador($ganador),
            'participantes' => array_map(function ($jugador) {
                return $this->formatearJugador($jugador);
            }, $jugadores),
        ];
        
        return $respuesta;
    }

    private function formatearJugador($jugador): ?array
    {
        if (!$jugador) {
            return null;
        }

        $datosJugador = [
            'id' => $jugador->id,
            'nombre' => $jugador->nombre,
            'genero' => $jugador->genero,
            'nivel_habilidad' => $jugador->nivel_habilidad,
        ];

        if ($jugador->genero === 'masculino') {
            $datosJugador['fuerza'] = $jugador->fuerza;
            $datosJugador['velocidad'] = $jugador->velocidad;
        } elseif ($jugador->genero === 'femenino') {
            $datosJugador['tiempo_reaccion'] = $jugador->tiempo_reaccion;
        }

        return $datosJugador;
    }

    public function buscarTorneosPorFecha(string $fecha): array
    {
        try {
            if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha)) {
                throw new \InvalidArgumentException("Formato de fecha inválido. Use el formato YYYY-MM-DD.");
            }

            $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
            if (!$fechaObj || $fechaObj->format('Y-m-d') !== $fecha) {
                throw new \InvalidArgumentException("Fecha inválida.");
            }

            $torneos = Torneo::whereDate('fecha', $fecha)
                             ->with(['ganador', 'participantes'])
                             ->get();

            if ($torneos->isEmpty()) {
                return ['mensaje' => 'No se encontraron torneos para la fecha especificada.'];
            }

            return $this->formatearTorneos($torneos);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    public function buscarTorneosPorGenero(string $genero): array
    {
        try {
            if (!in_array(strtolower($genero), ['masculino', 'femenino'])) {
                throw new \InvalidArgumentException("El género debe ser 'masculino' o 'femenino'.");
        }
        $torneos = Torneo::where('genero', $genero)->with(['ganador', 'participantes'])->get();
            return $this->formatearTorneos($torneos);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    public function obtenerTodosLosTorneos(): array
    {
        $torneos = Torneo::with(['ganador', 'participantes'])->get();
        return $this->formatearTorneos($torneos);
    }

    public function buscarTorneoPorId(int $id): array
    {
        $torneo = Torneo::with(['ganador', 'participantes'])->findOrFail($id);
        return $this->formatearTorneo($torneo);
    }

    private function formatearTorneos($torneos): array
    {
        return $torneos->map(function ($torneo) {
            return $this->formatearTorneo($torneo);
        })->toArray();
    }

    private function formatearTorneo($torneo): array
    {
        return [
            'id' => $torneo->id,
            'fecha' => $torneo->fecha->format('Y-m-d'),
            'genero' => $torneo->genero,
            'ganador' => $torneo->ganador ? $this->formatearJugador($torneo->ganador) : null,
            'participantes' => $torneo->participantes->map(function ($jugador) {
                return $this->formatearJugador($jugador);
            })->filter()->values()->toArray(),
        ];
    }

    private function crearJugadores(array $jugadoresData): array
    {
        return array_map([$this, 'crearJugador'], $jugadoresData);
    }

    private function crearJugador(array $jugadorData)
    {
        $atributos = [
            'nombre' => $jugadorData['nombre'],
            'genero' => $jugadorData['genero'],
            'nivel_habilidad' => $jugadorData['nivel_habilidad'],
        ];

        if ($jugadorData['genero'] === 'masculino') {
            $atributos['fuerza'] = $jugadorData['fuerza'];
            $atributos['velocidad'] = $jugadorData['velocidad'];
            return new JugadorMasculino($atributos);
        } elseif ($jugadorData['genero'] === 'femenino') {
            $atributos['tiempo_reaccion'] = $jugadorData['tiempo_reaccion'];
            return new JugadorFemenino($atributos);
        } else {
            throw new \InvalidArgumentException("Género no válido.");
        }
    }
}
