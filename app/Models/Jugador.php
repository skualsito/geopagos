<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jugador extends Model
{
    protected $table = 'jugadores';

    protected $fillable = [
        'nombre',
        'nivel_habilidad',
        'genero',
        'fuerza',
        'velocidad',
        'tiempo_reaccion',
    ];

    public function torneos()
    {
        return $this->belongsToMany(Torneo::class, 'torneo_participantes');
    }

    public function getNivelHabilidad()
    {
        return $this->nivel_habilidad;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->fill($attributes);
    }

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }
}
