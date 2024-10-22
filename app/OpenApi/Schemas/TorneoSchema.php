<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Torneo",
 *     @OA\Property(property="id", type="integer", format="int64", description="ID del torneo"),
 *     @OA\Property(property="fecha", type="string", format="date", description="Fecha del torneo"),
 *     @OA\Property(property="genero", type="string", enum={"masculino", "femenino", "mixto"}, description="Género del torneo"),
 *     @OA\Property(property="ganador", ref="#/components/schemas/Jugador", description="Ganador del torneo"),
 *     @OA\Property(
 *         property="participantes",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Jugador"),
 *         description="Lista de jugadores participantes"
 *     )
 * )
 */
class TorneoSchema
{
}
