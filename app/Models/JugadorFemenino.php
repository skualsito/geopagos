<?php

namespace App\Models;

class JugadorFemenino extends Jugador
{
    protected $fillable = ['nombre', 'genero', 'nivel_habilidad', 'tiempo_reaccion'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getTiempoReaccion()
    {
        return $this->tiempo_reaccion;
    }
}
