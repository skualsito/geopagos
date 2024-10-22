<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use DateTime;

class Torneo extends Model
{
    protected $fillable = [
        'fecha',
        'genero',
        'ganador_id',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function ganador(): BelongsTo
    {
        return $this->belongsTo(Jugador::class, 'ganador_id');
    }

    public function participantes(): BelongsToMany
    {
        return $this->belongsToMany(Jugador::class, 'torneo_participantes');
    }

    public static function buscarTorneosPorFecha(DateTime $fecha): array
    {
        return self::whereDate('fecha', $fecha)->get()->toArray();
    }

    public static function buscarTorneosPorGenero(string $genero): array
    {
        return self::where('genero', $genero)->get()->toArray();
    }
}
