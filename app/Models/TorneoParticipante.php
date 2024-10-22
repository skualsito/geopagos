<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TorneoParticipante extends Pivot
{
    protected $table = 'torneo_participantes';

    public $incrementing = false;

    protected $fillable = [
        'torneo_id',
        'jugador_id',
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}
