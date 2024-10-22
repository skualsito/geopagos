<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Jugador",
 *     required={"nombre", "edad", "genero", "nivel_habilidad"},
 *     @OA\Property(property="nombre", type="string", description="Nombre del jugador"),
 *     @OA\Property(property="edad", type="integer", description="Edad del jugador"),
 *     @OA\Property(property="genero", type="string", enum={"masculino", "femenino"}, description="Género del jugador"),
 *     @OA\Property(property="nivel_habilidad", type="integer", description="Nivel de habilidad del jugador"),
 *     @OA\Property(property="tiempo_reaccion", type="number", format="float", description="Tiempo de reacción (solo para jugadoras femeninas)"),
 *     @OA\Property(property="fuerza", type="integer", description="Fuerza del jugador (solo para jugadores masculinos)"),
 *     @OA\Property(property="velocidad", type="integer", description="Velocidad del jugador (solo para jugadores masculinos)")
 * )
 */
class JugadorSchema
{
}
