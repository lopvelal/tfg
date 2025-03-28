<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descripcion', 'fecha', 'hora_inicio', 'duracion', 'user_id', 'aula_id'];

    public function reservasUsuarios()
    {
        return $this->hasMany(ReservasUsuario::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
}
