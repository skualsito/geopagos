<?php

namespace App\Models;

class JugadorMasculino extends Jugador
{
    protected $fillable = ['nombre', 'genero', 'nivel_habilidad', 'fuerza', 'velocidad'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getFuerza()
    {
        return $this->fuerza;
    }

    public function getVelocidad()
    {
        return $this->velocidad;
    }
}
