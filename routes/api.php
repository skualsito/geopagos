<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TorneoController;

Route::post('/simular-torneo', [TorneoController::class, 'simularYGuardarTorneo']);
Route::get('/buscar-torneos-por-fecha', [TorneoController::class, 'buscarTorneosPorFecha']);
Route::get('/buscar-torneos-por-genero', [TorneoController::class, 'buscarTorneosPorGenero']);
Route::get('/obtener-todos-los-torneos', [TorneoController::class, 'obtenerTodosLosTorneos']);
Route::get('/buscar-torneo-por-id', [TorneoController::class, 'buscarTorneoPorId']);
