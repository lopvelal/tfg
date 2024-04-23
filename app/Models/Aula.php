<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'alias', 'descripcion', 'plazas', 'disponible'];

    public function reservas(){
        return $this->hasMany(Reserva::class);
    }
}
