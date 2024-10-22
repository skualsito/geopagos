<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TorneoService;
use DateTime;
use App\Models\Jugador;
use App\Models\Torneo;

/**
 * @OA\Tag(
 *     name="Torneos",
 *     description="API Endpoints de torneos"
 * )
 */


class TorneoController extends Controller
{
    protected $torneoService;

    public function __construct(TorneoService $torneoService)
    {
        $this->torneoService = $torneoService;
    }

    /**
     * @OA\Post(
     *     path="/api/simular-torneo",
     *     summary="Simula y guarda un torneo",
     *     tags={"Torneos"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="jugadores", type="array", @OA\Items(ref="#/components/schemas/Jugador"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Torneo simulado y guardado",
     *         @OA\JsonContent(ref="#/components/schemas/Torneo")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la solicitud"
     *     )
     * )
     */
    public function simularYGuardarTorneo(Request $request)
    {
        $jugadoresData = $request->input('jugadores');

        if (!is_array($jugadoresData) || empty($jugadoresData)) {
            return response()->json(['error' => 'Se requiere un array de jugadores válido'], 400);
        }

        try {
            $torneo = $this->torneoService->simularYGuardarTorneo($jugadoresData);
            return response()->json($torneo);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/buscar-torneos-por-fecha",
     *     summary="Busca torneos por fecha",
     *     tags={"Torneos"},
     *     @OA\Parameter(
     *         name="fecha",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de torneos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Torneo"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Formato de fecha inválido"
     *     )
     * )
     */
    public function buscarTorneosPorFecha(Request $request)
    {
        try {
            $fecha = $request->query('fecha');
            $torneos = $this->torneoService->buscarTorneosPorFecha($fecha);
            return response()->json($torneos);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al buscar torneos: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/buscar-torneos-por-genero",
     *     summary="Busca torneos por género",
     *     tags={"Torneos"},
     *     @OA\Parameter(
     *         name="genero",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de torneos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Torneo"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la solicitud"
     *     )
     * )
     */
    public function buscarTorneosPorGenero(Request $request)
    {
        try {
            $genero = $request->query('genero');
            $torneos = $this->torneoService->buscarTorneosPorGenero($genero);
            return response()->json($torneos);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al buscar torneos: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/obtener-todos-los-torneos",
     *     summary="Obtiene todos los torneos",
     *     tags={"Torneos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de todos los torneos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Torneo"))
     *     )
     * )
     */
    public function obtenerTodosLosTorneos()
    {
        try {
            $torneos = $this->torneoService->obtenerTodosLosTorneos();
            return response()->json($torneos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener torneos'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/buscar-torneo-por-id",
     *     summary="Busca un torneo por ID",
     *     tags={"Torneos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Torneo encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Torneo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Torneo no encontrado"
     *     )
     * )
     */
    public function buscarTorneoPorId(Request $request)
    {
        try {
            $id = $request->query('id');
            $torneo = $this->torneoService->buscarTorneoPorId($id);
            return response()->json($torneo);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Torneo no encontrado'], 404);
        }
    }
}
